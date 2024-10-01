<script setup>
import Button from '@/Components/Button.vue';

defineProps({
	type: {
		type: String,
		default: 'submit',
	},
});
</script>

<template>
	<Button
		:type="type"
		class="border border-transparent bg-primary text-white-light shadow-md shadow-reverse hover:bg-primary-dark focus:bg-primary-dark focus:ring-primary active:bg-primary-extradark"
	>
		<slot />
	</Button>
</template>
