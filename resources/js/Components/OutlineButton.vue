<script setup>
import Button from '@/Components/Button.vue';

defineProps({
	type: {
		type: String,
		default: 'button',
	},
	href: {
		type: String,
		default: '',
	},
});
</script>

<template>
	<Button
		:type="type"
		:href="href"
		class="border border-black-light bg-white text-black-dark shadow-sm hover:bg-white-dark focus:bg-white-dark focus:ring-black dark:border-neutral-500 dark:bg-black dark:text-white-light dark:hover:bg-black-light dark:focus:bg-black-light dark:focus:ring-neutral-500"
	>
		<slot />
	</Button>
</template>
