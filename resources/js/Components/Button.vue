<script setup>
defineProps({
	type: {
		type: String,
		default: 'submit',
	},
});
</script>

<template>
	<button
		:type="type"
		class="inline-flex items-center justify-center rounded-md px-4 py-2 text-center text-sm font-semibold uppercase tracking-widest duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-white enabled:transition enabled:active:scale-95 disabled:cursor-not-allowed disabled:opacity-50 dark:focus:ring-offset-black"
	>
		<slot />
	</button>
</template>
