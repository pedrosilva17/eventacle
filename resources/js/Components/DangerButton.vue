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
		class="active:bg-error-extradark border border-transparent bg-error text-black-dark shadow-sm hover:bg-error-dark focus:bg-error-dark focus:ring-error"
	>
		<slot />
	</Button>
</template>
