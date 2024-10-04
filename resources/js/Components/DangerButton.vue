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
		class="border border-transparent bg-error text-black-dark shadow-sm focus:bg-error-dark focus:ring-error enabled:hover:bg-error-dark enabled:active:bg-error-dark"
	>
		<slot />
	</Button>
</template>
