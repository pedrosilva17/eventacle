<script setup>
defineProps({
	message: String,
});
</script>

<template>
	<div v-show="message" class="mt-1 animate-shake md:col-span-2">
		<p class="text-sm text-error">
			{{ message }}
		</p>
	</div>
</template>
