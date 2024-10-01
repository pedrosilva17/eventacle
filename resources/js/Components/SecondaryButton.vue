<script setup>
import Button from '@/Components/Button.vue';

defineProps({
	type: {
		type: String,
		default: 'button',
	},
});
</script>

<template>
	<Button
		:type="type"
		class="border border-transparent bg-secondary text-black-dark shadow-sm hover:bg-secondary-dark focus:bg-secondary-dark focus:ring-secondary active:bg-secondary-extradark"
	>
		<slot />
	</Button>
</template>
