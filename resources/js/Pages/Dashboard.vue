<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import DashboardSection from '@/Components/DashboardSection.vue';
import { Link } from '@inertiajs/vue3';
import { needsBreakAll, plural } from '@/Lib/Utils';

const props = defineProps({
	eventsCreated: {
		type: Object,
	},
	eventsPredicted: {
		type: Object,
	},
	userPredictions: {
		type: Object,
	},
});

const options = {
	weekday: 'short',
	year: 'numeric',
	month: 'short',
	day: 'numeric',
	hour: 'numeric',
	minute: 'numeric',
	timeZoneName: 'short',
};
</script>

<template>
	<AppLayout title="Dashboard">
		<DashboardSection title="Your events">
			<template #icon>
				<i-ic-round-event aria-labelledby="your-events" />
			</template>
			<template #content>
				<p v-if="eventsCreated.length === 0">No upcoming events created yet. Time to change that!</p>
				<div v-else class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3">
					<Link
						:href="route('event.show', event)"
						v-for="event in eventsCreated"
						class="group flex flex-1 rounded-lg outline-none transition duration-300 ease-in-out focus:border-primary-extradark focus:ring-2 focus:ring-primary-extradark focus:ring-offset-2 focus:ring-offset-white dark:border-white-dark dark:focus:border-primary-extralight dark:focus:ring-primary-extralight dark:focus:ring-offset-black"
					>
						<section
							class="flex min-h-36 max-w-full flex-1 flex-col rounded-lg bg-white-light px-6 py-4 text-xl transition duration-300 ease-in-out hover:bg-primary-extralight group-focus:bg-primary-extralight dark:bg-black-light dark:hover:bg-primary-extradark dark:group-focus:bg-primary-extradark"
						>
							<h2
								class="-mx-6 -mt-4 h-full break-words rounded-t-lg bg-primary-extralight px-6 py-4 text-xl sm:text-2xl dark:bg-primary-extradark"
							>
								{{ event.name }}
							</h2>
							<span class="mt-4 flex flex-row">
								<p class="flex-1 text-sm">
									{{
										new Date(event.start_time)
											.toLocaleString('en-UK', options)
											.replace(/,(?=[^,]*$)/, ' •')
									}}
								</p>
								<p class="flex items-center justify-end gap-1 text-sm">
									{{ event.predictions.length / event.contests.length }}
									<i-ic-round-group class="text-base" aria-label="Number of users with predictions" />
								</p>
							</span>
						</section>
					</Link>
				</div>
			</template>
		</DashboardSection>
		<DashboardSection title="Your predictions">
			<template #icon>
				<i-ic-round-casino aria-labelledby="your-predictions" />
			</template>
			<template #content>
				<p v-if="eventsPredicted.length === 0" class="text-center">
					You have no predictions on upcoming events. Try your luck!
				</p>
				<div v-else class="flex flex-col gap-3">
					<Link
						:href="route('event.show', event)"
						v-for="event in eventsPredicted"
						class="group rounded-lg outline-none transition duration-300 ease-in-out focus:border-primary-extradark focus:ring-2 focus:ring-primary-extradark focus:ring-offset-2 focus:ring-offset-white dark:border-white-dark dark:focus:border-primary-extralight dark:focus:ring-primary-extralight dark:focus:ring-offset-black"
					>
						<div
							class="flex min-h-32 flex-1 flex-row justify-between rounded-lg bg-white-light px-6 py-4 text-xl transition duration-300 ease-in-out hover:bg-primary-extralight group-focus:bg-primary-extralight dark:bg-black-light dark:hover:bg-primary-extradark dark:group-focus:bg-primary-extradark"
						>
							<section
								class="-my-4 -ml-6 flex w-1/2 flex-col rounded-l-lg bg-primary-extralight px-6 py-4 sm:w-1/3 dark:bg-primary-extradark"
							>
								<h2 class="break-words text-xl sm:text-2xl">{{ event.name }}</h2>
								<p class="flex-1 text-sm">
									{{ new Date(event.start_time).toLocaleString('en-UK', options) }}
								</p>
								<p class="flex items-center justify-end gap-1 text-sm">
									{{ event.predictions.length / event.contests.length }}
									<i-ic-round-group class="text-base" aria-label="Users with predictions" />
								</p>
							</section>
							<div class="flex flex-1 flex-col gap-3 pl-4">
								<section v-for="prediction in userPredictions[event.id]">
									<h3
										class="text-lg sm:text-xl"
										:class="{
											'break-all': needsBreakAll(prediction.contest.name, 30),
											'max-md:break-all': needsBreakAll(prediction.contest.name, 20),
										}"
									>
										{{ prediction.contest.name }}
									</h3>
									<p
										class="flex flex-1 flex-row flex-wrap gap-2 text-base italic text-secondary-extradark sm:text-lg dark:text-secondary-extralight"
									>
										{{ prediction.prediction_name }}
										{{
											event.scoring_type === 'confidence points'
												? ' - ' + plural(prediction.points, 'point')
												: ''
										}}
									</p>
								</section>
							</div>
						</div>
					</Link>
				</div>
			</template>
		</DashboardSection>
	</AppLayout>
</template>
