<script setup>
import { dateTimeOptions, needsBreakAll, plural } from '@/Lib/Utils';
import { Link } from '@inertiajs/vue3';

defineProps({
	event: {
		type: Object,
	},
	predictions: {
		type: Object,
	},
});
</script>

<template>
	<Link
		:href="route('event.show', event)"
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
					{{
						new Date(event.start_time).toLocaleString('en-UK', dateTimeOptions).replace(/,(?=[^,]*$)/, ' •')
					}}
				</p>
				<p class="flex items-center justify-end gap-1 text-sm">
					{{ event.predictions.length / event.contests.length }}
					<i-ic-round-group class="text-base" aria-label="Users with predictions" />
				</p>
			</section>
			<div class="flex flex-1 flex-col gap-3 pl-4">
				<section v-for="prediction in predictions">
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
							event.scoring_type === 'confidence points' ? ' - ' + plural(prediction.points, 'point') : ''
						}}
					</p>
				</section>
			</div>
		</div>
	</Link>
</template>
