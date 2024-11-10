<script setup>
import Container from '@/Components/Container.vue';
import DangerButton from '@/Components/DangerButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Accordion, AccordionItem, AccordionTrigger, AccordionContent } from '@/Components/shadcn/accordion';
import AppLayout from '@/Layouts/AppLayout.vue';
import { needsBreakAll, plural } from '@/Lib/Utils';
import { Link, router, usePage } from '@inertiajs/vue3';

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

const leaderboardColor = (rank) => {
	if (rank > 3) return 'bg-transparent';
	return ['bg-amber-400 text-black', 'bg-slate-500 text-white', 'bg-amber-700 text-white'][rank - 1];
};

const deleteEvent = () => {
	if (confirm('Are you sure you want to delete this event?')) {
		router.delete(
			route('event.delete', {
				event: props.event,
			}),
		);
	}
};

const getRanks = (points) => {
	const sorted = [...new Set(points)].sort((a, b) => b - a);
	const rank = new Map(sorted.map((x, i) => [x, i + 1]));
	return points.map((x) => rank.get(x));
};
const ranks = getRanks(props.event.leaderboard.map((entry) => entry.score));
</script>

<template>
	<AppLayout :title="event.name">
		<span class="grid-rows-auto grid auto-rows-min grid-cols-2 gap-3">
			<Container class="col-span-2 flex-col">
				<span class="flex flex-col gap-3 sm:flex-row">
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
					<template class="flex flex-col py-1 text-lg sm:text-xl">
						<span class="flex flex-row-reverse items-center justify-end gap-2 sm:flex-row sm:items-start">
							<p>{{ date }}</p>
							<i-ic-round-calendar-today aria-label="Event Date" class="text-secondary" />
						</span>
						<span class="flex flex-row-reverse items-center justify-end gap-2 sm:flex-row">
							<p>{{ time }}</p>
							<i-ic-round-access-time-filled aria-label="Event Time" class="text-secondary" />
						</span>
						<span class="flex flex-row-reverse items-center justify-end gap-2 sm:flex-row">
							<p>{{ event.creator.name }}</p>
							<i-ic-round-person aria-label="Creator Name" class="text-secondary" />
						</span>
						<span class="flex flex-row-reverse items-center justify-end gap-2 sm:flex-row">
							<p>{{ scoringType }}</p>
							<i-ic-round-plus-one aria-label="Scoring Type" class="text-xl text-secondary" />
						</span>
					</template>
				</span>
				<span class="flex flex-row gap-5">
					<Link
						v-if="new Date(event.start_time) > Date.now()"
						:href="route('event.prediction-form', event)"
						tabindex="-1"
					>
						<PrimaryButton>{{ hasPrediction ? 'Edit prediction' : 'Make a Prediction' }}</PrimaryButton>
					</Link>
					<DangerButton
						class="w-fit"
						v-if="$page.props.auth.user?.id === props.event.creator_id"
						@click="deleteEvent"
						>Delete Event</DangerButton
					>
				</span>
			</Container>
			<Container class="col-span-2 max-h-screen flex-col overflow-x-hidden">
				<h2 class="text-2xl">Contests</h2>
				<ul class="grid grid-cols-1 gap-5 md:grid-cols-2">
					<li v-for="contest in event.contests" :key="contest.id" class="flex h-full flex-col">
						<p class="break-words text-xl">
							{{ contest.name }}
						</p>
						<p>
							{{ contest.description }}
						</p>
						<span class="relative mt-2 flex flex-1">
							<span
								class="flex flex-1 flex-row items-center justify-between gap-5 overflow-x-auto rounded-lg bg-white-light px-4 pb-4 pt-9 dark:bg-black-light"
							>
								<p class="absolute left-4 top-2 italic opacity-60">
									Options ({{ contest.options.split('|SEP|').length }})
								</p>
								<p
									class="relative min-w-max rounded-md bg-primary-extralight px-2 text-center dark:bg-primary-extradark"
									v-for="option in contest.options
										.split('|SEP|')
										.sort((opt) => contest.result !== opt)"
								>
									<i-ic-baseline-emoji-events
										class="absolute -left-2 -top-2 text-amber-400"
										v-if="contest.result === option"
									/>
									{{ option }}
								</p>
							</span>
						</span>
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
									<span
										:class="leaderboardColor(ranks[index])"
										class="z-5 relative m-auto flex w-6 justify-center rounded-full"
									>
										{{ ranks[index] }}
									</span>
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
			<Container class="col-span-2 max-h-screen flex-col overflow-x-hidden">
				<h2 class="text-2xl">Predictions</h2>
				<template v-if="Object.keys(predictionsByContest).length > 0">
					<Accordion type="multiple" as="ul" collapsible class="flex flex-col gap-5">
						<AccordionItem as="li" :value="key" v-for="key in Object.keys(predictionsByContest)">
							<AccordionTrigger
								class="text-left text-lg"
								:class="{
									'max-md:break-all': needsBreakAll(
										event.contests.find((c) => c.id.toString() === key).name,
										25,
									),
								}"
							>
								{{ event.contests.find((c) => c.id.toString() === key).name }}
							</AccordionTrigger>
							<AccordionContent>
								<p
									v-for="prediction in predictionsByContest[key]"
									class="flex flex-col gap-1 pb-3 md:flex-row md:items-center md:pb-0"
								>
									{{ prediction.user_name
									}}<i-ic-round-keyboard-arrow-right class="hidden md:inline-flex" /><span
										class="inline-flex text-secondary-extradark dark:text-secondary-extralight"
										>{{ prediction.prediction_name }}</span
									>
									{{
										event.scoring_type === 'confidence points'
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
								</p>
							</AccordionContent>
						</AccordionItem>
					</Accordion>
				</template>
				<template v-else>
					<p>No predictions made yet.</p>
				</template>
			</Container>
		</span>
	</AppLayout>
</template>
