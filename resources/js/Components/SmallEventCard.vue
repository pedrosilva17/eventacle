<script setup>
import { dateTimeOptions } from '@/Lib/Utils';
import { Link } from '@inertiajs/vue3';

defineProps({
	event: {
		type: Object,
	},
});
</script>

<template>
	<Link
		:href="route('event.show', event)"
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
						new Date(event.start_time).toLocaleString('en-UK', dateTimeOptions).replace(/,(?=[^,]*$)/, ' •')
					}}
				</p>
				<p class="flex items-center justify-end gap-1 text-sm">
					{{ event.predictions.length / event.contests.length }}
					<i-ic-round-group class="text-base" aria-label="Number of users with predictions" />
				</p>
			</span>
		</section>
	</Link>
</template>
