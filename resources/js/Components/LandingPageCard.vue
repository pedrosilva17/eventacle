<script setup>
defineProps({
	title: {
		type: String,
	},
});
</script>

<template>
	<div
		id="{{ title.toLowerCase().replace(' ', '-') }}"
		class="flex max-w-sm flex-row gap-3 rounded-lg border border-black bg-white-light px-6 py-4 shadow-md dark:border-white dark:bg-black-dark"
	>
		<section class="flex flex-1 flex-col gap-3">
			<div class="flex items-center gap-3">
				<slot name="icon" />
				<h3 class="flex text-xl font-bold sm:text-2xl">{{ title }}</h3>
			</div>
			<p class="flex text-lg [overflow-wrap:anywhere]">
				<slot name="content" />
			</p>
		</section>
	</div>
</template>
