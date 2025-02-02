<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import DeleteUserForm from '@/Pages/Profile/Partials/DeleteUserForm.vue';
import LogoutOtherBrowserSessionsForm from '@/Pages/Profile/Partials/LogoutOtherBrowserSessionsForm.vue';
import SectionBorder from '@/Components/SectionBorder.vue';
import TwoFactorAuthenticationForm from '@/Pages/Profile/Partials/TwoFactorAuthenticationForm.vue';
import UpdatePasswordForm from '@/Pages/Profile/Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from '@/Pages/Profile/Partials/UpdateProfileInformationForm.vue';
import DialogModal from '@/Components/DialogModal.vue';
import { ref } from 'vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Container from '@/Components/Container.vue';
import { needsBreakAll, toggleAccordions } from '@/Lib/Utils';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { Link, usePage } from '@inertiajs/vue3';
import Accordion from '@/Components/shadcn/accordion/Accordion.vue';
import AccordionItem from '@/Components/shadcn/accordion/AccordionItem.vue';
import AccordionTrigger from '@/Components/shadcn/accordion/AccordionTrigger.vue';
import AccordionContent from '@/Components/shadcn/accordion/AccordionContent.vue';
import SmallEventCard from '@/Components/SmallEventCard.vue';
import OutlineButton from '@/Components/OutlineButton.vue';

defineProps({
	confirmsTwoFactorAuthentication: Boolean,
	sessions: Array,
});

const dateOptions = {
	month: 'long',
	day: 'numeric',
};
const page = usePage();
const show = ref(false);
const openStates = ref(new Array(Object.keys(page.props.eventsPredicted).length).fill(false));
const creationDate = new Date(page.props.auth.user.created_at);
</script>

<template>
	<AppLayout title="Profile">
		<div class="grid-rows-auto grid auto-rows-min grid-cols-2 gap-3">
			<Container class="col-span-2 flex-col">
				<div class="flex flex-1 flex-row justify-between gap-3">
					<h1
						:class="{
							'break-all': needsBreakAll($page.props.auth.user.name, 35),
							'max-md:break-all': needsBreakAll($page.props.auth.user.name, 15),
						}"
						class="text-2xl font-bold lg:break-words lg:text-5xl"
					>
						{{ $page.props.auth.user.name }}
					</h1>
					<PrimaryButton id="settings" aria-label="Edit profile" @click="show = true" class="h-fit">
						<i-ic-round-settings class="text-lg" aria-labelledby="settings" />
					</PrimaryButton>
				</div>
				<p class="-mt-8 flex break-all text-sm text-black-light dark:text-white-dark">
					{{ $page.props.auth.user.email }}
				</p>
				<ul class="flex flex-col items-center justify-around text-4xl max-sm:gap-8 sm:flex-row">
					<li class="flex h-full flex-col items-center gap-3">
						<p class="relative flex w-full flex-1 items-center justify-center">
							{{ creationDate.getFullYear() }}
							<span
								class="absolute text-sm text-black-light max-sm:-top-4 sm:bottom-2 dark:text-white-dark"
								>{{ creationDate.toLocaleDateString('en-UK', dateOptions) }}</span
							>
						</p>
						<p class="text-base">Date joined</p>
					</li>
					<li class="flex h-full flex-col items-center gap-3">
						<p class="flex flex-1 items-center">{{ $page.props.eventsPredicted.length }}</p>
						<p class="text-base">Events foreseen</p>
					</li>
					<li class="flex h-full flex-col items-center text-2xl">
						<p class="flex flex-row items-center gap-3">
							{{ $page.props.podiums[0] }}
							<i-ic-round-emoji-events aria-label="First places" class="text-amber-400" />
						</p>
						<p class="flex flex-row items-center gap-3">
							{{ $page.props.podiums[1] }}
							<i-ic-round-emoji-events aria-label="Second places" class="text-gray-400" />
						</p>
						<p class="flex flex-row items-center gap-3">
							{{ $page.props.podiums[2] }}
							<i-ic-round-emoji-events aria-label="Third places" class="text-amber-800" />
						</p>
						<p class="mt-3 text-base">Podiums conquered</p>
					</li>
				</ul>
			</Container>
			<Container class="col-span-2 max-h-screen flex-col">
				<h2 class="text-2xl">Events Created</h2>
				<p v-if="$page.props.eventsCreated.length === 0">
					No upcoming events created yet. Time to change that!
				</p>
				<div v-else class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3">
					<template v-for="event in $page.props.eventsCreated">
						<SmallEventCard :event="event" />
					</template>
				</div>
			</Container>
			<Container class="col-span-2 max-h-screen flex-col">
				<h2 class="text-2xl">Prediction History</h2>
				<span class="absolute right-6 top-4 flex flex-row justify-between gap-3">
					<SecondaryButton
						v-if="Object.keys($page.props.predictionsByContest).length > 0"
						aria-label="Open or close prediction drawers"
						@click="toggleAccordions('.accordion')"
					>
						<span class="text-lg">
							<i-ic-round-expand v-if="openStates.some((s) => s === false)" />
							<i-ic-round-compress v-else />
						</span>
					</SecondaryButton>
				</span>
				<template v-if="Object.keys($page.props.predictionsByContest).length > 0">
					<Accordion type="multiple" as="ul" collapsible class="flex h-full flex-col gap-3 overflow-y-auto">
						<AccordionItem
							as="li"
							:value="event.id.toString()"
							v-for="(event, idx) in $page.props.eventsPredicted"
							class="relative"
						>
							<PrimaryButton
								:id="'event-link-' + idx"
								:href="'/event/' + event.slug"
								aria-label="Open event page"
								class="absolute left-2 top-3 m-auto"
							>
								<i-ic-round-open-in-new :aria-labelledby="'event-link-' + idx" />
							</PrimaryButton>
							<AccordionTrigger
								@click="openStates[idx] = !openStates[idx]"
								class="accordion ml-16 text-left text-lg"
								:class="{
									'max-md:break-all': needsBreakAll(event.name, 20),
								}"
							>
								{{ event.name }}
							</AccordionTrigger>
							<AccordionContent class="flex flex-col gap-1">
								<div
									v-for="contest in event.contests"
									class="flex flex-col md:flex-row md:items-center md:gap-1"
									:key="contest"
								>
									<p class="flex md:w-1/2 md:justify-end md:text-right">{{ contest.name }}</p>
									<span class="hidden text-center text-base md:inline-flex md:flex-1">></span>
									<div
										class="inline-flex items-end justify-between gap-1 text-secondary-extradark md:w-1/2 dark:text-secondary-extralight"
									>
										{{ $page.props.predictionsByContest[contest.id][0].prediction_name }}
										{{
											event.scoringType === 'Confidence Points'
												? `(${plural($page.props.predictionsByContest[contest.id][0].prediction_name, 'point')})`
												: ''
										}}
										<span v-if="contest.result">
											<i-ic-round-check
												class="inline-flex text-success"
												v-if="
													$page.props.predictionsByContest[contest.id][0].prediction_name ===
													contest.result
												"
											/>
											<i-ic-round-close class="inline-flex text-error" v-else />
										</span>
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
			<Container class="col-span-2 max-h-screen flex-col">
				<h2 class="text-2xl">Stats</h2>
				<div
					class="flex flex-1 flex-col justify-between gap-3 rounded-lg bg-white-light px-6 py-4 sm:text-lg dark:bg-black-light"
				>
					<span class="flex w-full flex-row items-center gap-2 sm:flex-row">
						<i-ic-round-edit-calendar aria-labelledby="events-created" class="text-secondary" />
						<p id="events-created">Events created</p>
						<p class="ml-auto flex">{{ $page.props.eventsCreated.length }}</p>
					</span>
					<span class="flex flex-row items-center gap-2 sm:flex-row">
						<i-ic-round-casino aria-labelledby="predictions-made" class="text-secondary" />
						<p id="predictions-made">Predictions made</p>
						<p class="ml-auto flex">{{ $page.props.eventsPredicted.length }}</p>
					</span>
					<span class="flex flex-row items-center gap-2 sm:flex-row">
						<i-ic-round-emoji-events aria-labelledby="wins" class="text-secondary" />
						<p id="wins">Wins</p>
						<p class="ml-auto flex">{{ $page.props.podiums[0] }}</p>
					</span>
					<span class="flex flex-row items-center gap-2 sm:flex-row">
						<i-ic-round-percentage aria-labelledby="win-rate" class="text-secondary" />
						<p id="win-rate">Win rate</p>
						<p class="ml-auto flex">{{ $page.props.winRate }}%</p>
					</span>
					<span class="flex flex-row items-center gap-2 sm:flex-row">
						<i-ic-round-leaderboard aria-labelledby="podium-placements" class="text-secondary" />
						<p id="podium-placements">Podium placements</p>
						<p class="ml-auto flex">{{ $page.props.podiumCount }}</p>
					</span>
				</div>
			</Container>
		</div>
	</AppLayout>
	<DialogModal :show="show" @close="show = false">
		<template #title> Settings </template>
		<template #content>
			<div v-if="$page.props.jetstream.canUpdateProfileInformation">
				<UpdateProfileInformationForm :user="$page.props.auth.user" />

				<SectionBorder />
			</div>

			<div v-if="$page.props.jetstream.canUpdatePassword">
				<UpdatePasswordForm class="mt-10 sm:mt-0" />

				<SectionBorder />
			</div>

			<div v-if="$page.props.jetstream.canManageTwoFactorAuthentication">
				<TwoFactorAuthenticationForm
					:requires-confirmation="confirmsTwoFactorAuthentication"
					class="mt-10 sm:mt-0"
				/>

				<SectionBorder />
			</div>

			<LogoutOtherBrowserSessionsForm :sessions="sessions" class="mt-10 sm:mt-0" />

			<template v-if="$page.props.jetstream.hasAccountDeletionFeatures">
				<SectionBorder />

				<DeleteUserForm class="mt-10 sm:mt-0" />
			</template>
		</template>
		<template #footer>
			<SecondaryButton @click="show = false"> Close </SecondaryButton>
		</template>
	</DialogModal>
</template>
