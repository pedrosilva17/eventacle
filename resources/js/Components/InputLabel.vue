<script setup>
defineProps({
	value: String,
});
</script>

<template>
	<label class="block text-sm font-medium text-black-dark dark:text-white-light">
		<span v-if="value">{{ value }}</span>
		<span v-else><slot /></span>
	</label>
</template>
