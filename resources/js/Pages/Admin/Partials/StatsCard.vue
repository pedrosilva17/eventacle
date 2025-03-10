<script setup>
defineProps({
	title: {
		type: String,
		required: true,
	},
	stats: {
		type: Object,
		required: true,
		validator: (value) => {
			return ['hour', 'day', 'week'].every((key) => key in value);
		},
	},
});
</script>

<template>
	<section>
		<h3 class="text-2xl">{{ title }}</h3>
		<div class="flex flex-col justify-between gap-3 text-sm text-black sm:flex-row dark:text-white">
			<section class="flex flex-col gap-2">
				<h4>Last Hour</h4>
				<p class="text-3xl font-bold">{{ stats.hour }}</p>
			</section>
			<section class="flex flex-col gap-2">
				<h4>Last Day</h4>
				<p class="text-3xl font-bold">{{ stats.day }}</p>
			</section>
			<section class="flex flex-col gap-2">
				<h4>Last Week</h4>
				<p class="text-3xl font-semibold">{{ stats.week }}</p>
			</section>
		</div>
	</section>
</template>
