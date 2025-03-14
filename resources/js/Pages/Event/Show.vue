<script setup>
import Container from '@/Components/Container.vue';
import DangerButton from '@/Components/DangerButton.vue';
import DialogModal from '@/Components/DialogModal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import Dropdown from '@/Components/Dropdown.vue';
import { Accordion, AccordionItem, AccordionTrigger, AccordionContent } from '@/Components/shadcn/accordion';
import AppLayout from '@/Layouts/AppLayout.vue';
import { needsBreakAll, plural, toggleAccordions } from '@/Lib/Utils';
import { router, usePage } from '@inertiajs/vue3';
import { reactive, ref } from 'vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import { toast } from 'vue-sonner';
import OutlineButton from '@/Components/OutlineButton.vue';

const props = defineProps({
	event: {
		type: Object,
	},
	predictionsByContest: {
		type: Object,
	},
});
const page = usePage();

const dateOptions = {
	weekday: 'long',
	year: 'numeric',
	month: 'long',
	day: 'numeric',
};

const timeOptions = {
	hour: 'numeric',
	minute: 'numeric',
	timeZoneName: 'short',
};

const canEdit = new Date(props.event.start_time) > Date.now();
const isCreator = page.props.auth.user?.id === props.event.creator_id;
const isAdmin = page.props.auth.user?.is_admin;

const show = ref(false);
const openStates = ref(new Array(Object.keys(props.predictionsByContest).length).fill(false));
const dateTime = new Date(props.event.start_time);
const date = dateTime.toLocaleDateString('en-UK', dateOptions);
const time = dateTime.toLocaleTimeString('en-UK', timeOptions);
const hasPrediction =
	!!page.props.auth.user && !!props.event.predictions.find((e) => e.user_id === page.props.auth.user.id);
const scoringType = props.event.scoring_type
	.split(' ')
	.map((w) => {
		return w.slice(0, 1).toUpperCase() + w.slice(1);
	})
	.join(' ');

const predictionsByContest = ref(props.predictionsByContest);
const predictionAlphaSort = (a, b) => (b.user_name.toLowerCase() < a.user_name.toLowerCase() ? 1 : -1);
const predictionPointSort = (a, b) => b.points - a.points;
const sortFunctions = [predictionPointSort, predictionAlphaSort];
const currentFunctionIndex = ref(0);
const activeSort = ref(sortFunctions[currentFunctionIndex.value]);

const changeSortFunction = () => {
	currentFunctionIndex.value = 1 - currentFunctionIndex.value;
	activeSort.value = sortFunctions[currentFunctionIndex.value];
	Object.keys(predictionsByContest.value).forEach((contest) => {
		predictionsByContest.value[contest] = predictionsByContest.value[contest].sort(activeSort.value);
	});
};

const leaderboardColor = (rank) => {
	if (rank > 3) return 'bg-transparent';
	return ['bg-amber-400 text-black', 'bg-slate-500 text-white', 'bg-amber-700 text-white'][rank - 1];
};

const deleteEvent = () => {
	router.delete(
		route('event.delete', {
			event: props.event,
		}),
	);
};

const getRanks = (points) => {
	const sorted = [...new Set(points)].sort((a, b) => b - a);
	const rank = new Map(sorted.map((x, i) => [x, i + 1]));
	return points.map((x) => rank.get(x));
};
const ranks = getRanks(props.event.leaderboard.map((entry) => entry.score));

const copied = ref(false);
const copyEventLink = async () => {
	try {
		await navigator.clipboard.writeText(document.URL).then(() => {
			copied.value = true;
			toast.success('Copied to clipboard!');
		});
	} catch (error) {
		toast.error('An error occurred. Try copying the link manually!');
	}
};
</script>

<template>
	<AppLayout :title="event.name">
		<div class="grid-rows-auto grid auto-rows-min grid-cols-2 gap-3">
			<Container class="col-span-2 flex-col">
				<div class="flex flex-col gap-3 sm:flex-row">
					<template class="flex flex-1 flex-col gap-2">
						<h1
							:class="{
								'break-all': needsBreakAll(props.event.name, 35),
								'max-md:break-all': needsBreakAll(props.event.name, 15),
							}"
							class="text-2xl font-bold lg:break-words lg:text-4xl"
						>
							{{ event.name }}
						</h1>
						<p>{{ event.description }}</p>
					</template>
					<div class="flex flex-col py-1 text-lg sm:text-xl">
						<span class="flex flex-row-reverse items-center justify-end gap-2 sm:flex-row sm:items-start">
							<p>{{ date }}</p>
							<i-ic-round-calendar-today aria-label="Event Date" class="text-secondary" />
						</span>
						<span class="flex flex-row-reverse items-center justify-end gap-2 sm:flex-row">
							<p>{{ time }}</p>
							<i-ic-round-access-time-filled aria-label="Event Time" class="text-secondary" />
						</span>
						<span class="flex flex-row-reverse items-center justify-end gap-2 sm:flex-row">
							<p class="max-w-64 text-left sm:text-right">{{ event.creator.name }}</p>
							<i-ic-round-person aria-label="Creator Name" class="text-secondary" />
						</span>
						<span class="flex flex-row-reverse items-center justify-end gap-2 sm:flex-row">
							<p>{{ scoringType }}</p>
							<i-ic-round-plus-one aria-label="Scoring Type" class="text-xl text-secondary" />
						</span>
					</div>
				</div>
				<div class="flex w-full flex-col gap-3 sm:flex-row">
					<div v-if="canEdit || isAdmin" class="flex flex-row gap-3">
						<PrimaryButton :href="route('event.prediction', event)" class="w-fit">{{
							hasPrediction ? 'Change prediction' : 'Make a Prediction'
						}}</PrimaryButton>
						<OutlineButton @click="copyEventLink" class="w-fit">
							<Transition name="scale" mode="out-in">
								<i-ic-round-share v-if="!copied" aria-label="Copy event link" class="text-lg" />
								<i-ic-round-check v-else aria-label="Link copied" class="text-lg" />
							</Transition>
						</OutlineButton>
					</div>
					<div v-if="isCreator || isAdmin" class="flex flex-1 flex-row gap-3 sm:justify-end">
						<PrimaryButton
							v-if="(!canEdit && !event.has_winners) || isAdmin"
							:href="route('event.winners', event)"
						>
							Finish Event
						</PrimaryButton>
						<OutlineButton v-if="canEdit || isAdmin" :href="route('event.edit', event)">
							<i-ic-round-edit aria-label="Edit event" class="text-lg" />
						</OutlineButton>
						<DangerButton @click="show = !show">
							<i-ic-round-delete aria-label="Delete event" class="text-lg" />
						</DangerButton>
					</div>
				</div>
			</Container>
			<Container class="col-span-2 max-h-screen flex-col overflow-x-hidden">
				<h2 class="text-2xl">Contests</h2>
				<ul class="grid grid-cols-1 gap-3 overflow-y-auto p-1 md:grid-cols-2">
					<li v-for="contest in event.contests" :key="contest.id" class="flex h-full flex-col">
						<p :class="{ 'flex-1': !contest.description }" class="break-words text-xl">
							{{ contest.name }}
						</p>
						<p v-if="contest.description" class="flex-1">
							{{ contest.description }}
						</p>
						<div class="relative mt-2 flex">
							<div
								class="flex flex-1 flex-row items-center justify-between gap-3 overflow-x-auto rounded-lg bg-white-light px-4 pb-4 pt-9 dark:bg-black-light"
							>
								<p class="absolute left-4 top-2 italic opacity-60">Options</p>
								<p
									class="relative min-w-max rounded-md bg-primary-extralight px-2 text-center dark:bg-primary-extradark"
									v-for="option in contest.options
										.split('|SEP|')
										.sort()
										.sort(
											(opt1, opt2) =>
												(opt2 === contest.result) - (opt1 === contest.result) ||
												opt2.toLowerCase() - opt1.toLowerCase(),
										)"
								>
									<i-ic-round-emoji-events
										class="absolute -left-2 -top-2 text-amber-400"
										v-if="contest.result === option"
									/>
									{{ option }}
								</p>
							</div>
						</div>
					</li>
				</ul>
			</Container>
			<Container class="col-span-2 max-h-screen flex-col overflow-x-hidden">
				<h2 class="text-2xl">Leaderboard</h2>
				<template v-if="event.leaderboard.length > 0">
					<table class="text-center">
						<thead>
							<tr>
								<th>Rank</th>
								<th>Name</th>
								<th>Points</th>
							</tr>
						</thead>
						<tbody>
							<tr v-for="(entry, index) in event.leaderboard" class="h-12">
								<td class="relative h-full justify-center text-center">
									<svg
										v-if="ranks[index] <= 3"
										class="absolute left-1/2 top-1/2 h-8 w-5 -translate-x-1/2 fill-primary"
										xmlns="http://www.w3.org/2000/svg"
										viewBox="0 0 1080 1920"
									>
										<path d="m1080 0 v1920l-540-633-540 633v-1920z" />
									</svg>
									<div
										:class="leaderboardColor(ranks[index])"
										class="z-5 relative m-auto flex w-6 justify-center rounded-full"
									>
										{{ ranks[index] }}
									</div>
								</td>
								<td>{{ entry.user_name }}</td>
								<td>{{ entry.score }}</td>
							</tr>
						</tbody>
					</table>
				</template>

				<template v-else>
					<p>No leaderboard available yet.</p>
				</template>
			</Container>
			<Container class="col-span-2 max-h-screen flex-col">
				<h2 class="text-2xl">Predictions</h2>
				<div class="absolute right-6 top-4 flex flex-row justify-between gap-3">
					<SecondaryButton
						v-if="scoringType === 'Confidence Points'"
						@click="changeSortFunction"
						:aria-label="
							activeSort.name === 'predictionAlphaSort' ? 'alphabetical order' : 'confidence point order'
						"
						class="gap-1 text-xs sm:text-lg"
					>
						Sort by:
						<i-ic-round-sort-by-alpha v-if="activeSort.name === 'predictionAlphaSort'" class="text-lg" />
						<i-ic-round-exposure-plus-1 v-if="activeSort.name === 'predictionPointSort'" class="text-lg" />
					</SecondaryButton>
					<SecondaryButton
						v-if="Object.keys(predictionsByContest).length > 0"
						aria-label="Open or close prediction drawers"
						@click="toggleAccordions('.accordion')"
					>
						<div class="text-lg">
							<i-ic-round-expand v-if="openStates.some((s) => s === false)" />
							<i-ic-round-compress v-else />
						</div>
					</SecondaryButton>
				</div>
				<template v-if="Object.keys(predictionsByContest).length > 0">
					<Accordion type="multiple" as="ul" collapsible class="flex flex-col gap-3 overflow-y-auto">
						<AccordionItem as="li" :value="key" v-for="(key, idx) in Object.keys(predictionsByContest)">
							<AccordionTrigger
								@click="openStates[idx] = !openStates[idx]"
								class="accordion text-left text-lg"
								:class="{
									'max-md:break-all': needsBreakAll(predictionsByContest[key][0].contest_name, 20),
								}"
							>
								{{ predictionsByContest[key][0].contest_name }}
							</AccordionTrigger>
							<AccordionContent class="flex flex-col gap-1">
								<div
									v-for="prediction in predictionsByContest[key]"
									class="flex flex-col md:flex-row md:items-center md:gap-1"
									:key="prediction"
								>
									<p class="flex md:w-1/2 md:justify-end md:text-right">
										{{ prediction.user_name }}
									</p>
									<div class="hidden text-base md:inline-flex md:flex-1 md:text-center">></div>
									<div
										class="inline-flex items-end justify-between gap-1 text-secondary-extradark md:w-1/2 md:justify-start dark:text-secondary-extralight"
									>
										{{ prediction.prediction_name }}
										{{
											scoringType === 'Confidence Points'
												? `(${plural(prediction.points, 'point')})`
												: ''
										}}
										<template v-if="event.contests.find((c) => c.id.toString() === key).result">
											<i-ic-round-check
												class="inline-flex text-success"
												v-if="
													prediction.prediction_name ===
													event.contests.find((c) => c.id.toString() === key).result
												"
											/>
											<i-ic-round-close class="inline-flex text-error" v-else />
										</template>
									</div>
								</div>
							</AccordionContent>
						</AccordionItem>
					</Accordion>
				</template>
				<template v-else>
					<p>No predictions made yet.</p>
				</template>
			</Container>
		</div>
		<DialogModal :show="show" @close="show = false">
			<template #title>Delete Event</template>
			<template #content>
				Are you sure you want to delete your event? You will not be able to restore it once you confirm this
				action. If the winners have already been published, this event's leaderboard will be preserved.
			</template>
			<template #footer>
				<div class="flex gap-3">
					<SecondaryButton @click="show = false">Cancel</SecondaryButton>
					<DangerButton @click="deleteEvent"> Delete </DangerButton>
				</div>
			</template>
		</DialogModal>
	</AppLayout>
</template>
