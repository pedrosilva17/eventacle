<script setup>
const props = defineProps({
	title: {
		type: String,
		default: '',
	},
});
const classes = 'relative flex flex-col overflow-hidden bg-white p-4 shadow-xl sm:rounded-xl dark:bg-black';
</script>

<template>
	<section v-if="title" :class="classes">
		<span
			class="text-xls absolute right-4 top-4 rounded-lg bg-white-light p-2 text-secondary sm:text-3xl dark:bg-black-light"
		>
			<slot name="icon" />
		</span>
		<h1
			:id="title.toLowerCase().replace(' ', '-')"
			class="pb-6 pt-1 text-center text-xl font-bold sm:pt-2 sm:text-3xl"
		>
			{{ title }}
		</h1>
		<slot name="content" />
	</section>
	<div v-else :class="classes">
		<slot name="icon" />
		<slot name="content" />
	</div>
</template>
