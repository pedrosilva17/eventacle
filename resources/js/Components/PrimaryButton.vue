<script setup>
import Button from '@/Components/Button.vue';

defineProps({
	type: {
		type: String,
		default: 'submit',
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
		class="border border-transparent bg-primary text-white-light shadow-md shadow-reverse hover:bg-primary-dark focus:bg-primary-dark focus:ring-primary active:bg-primary-extradark"
	>
		<slot />
	</Button>
</template>
