<script setup>
import Container from './Container.vue';

const props = defineProps({
	title: {
		type: String,
		default: '',
	},
});
</script>

<template>
	<section v-if="title">
		<h2
			:id="title.toLowerCase().replace(' ', '-')"
			class="flex w-full items-center justify-between pb-6 pt-1 text-center text-xl font-bold max-sm:px-4 sm:pt-2 sm:text-3xl"
		>
			{{ title }}
			<span
				class="right-4 top-4 flex w-fit justify-end rounded-lg bg-white-light p-2 text-xl text-secondary sm:text-3xl dark:bg-black-light"
			>
				<slot name="icon" />
			</span>
		</h2>
		<Container class="mb-8 flex-col">
			<slot name="content" />
		</Container>
	</section>
	<div v-else :class="classes">
		<slot name="icon" />
		<slot name="content" />
	</div>
</template>
