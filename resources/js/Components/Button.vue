<script setup>
import { Link } from '@inertiajs/vue3';

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

const classes =
	'inline-flex items-center justify-center rounded-md px-4 py-2 text-center text-sm font-semibold uppercase tracking-widest duration-150 ease-in-out outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-white enabled:transition enabled:active:scale-95 disabled:cursor-not-allowed disabled:opacity-50 dark:focus:ring-offset-black';
</script>

<template>
	<Link v-if="href" :href="href" :class="classes">
		<slot />
	</Link>
	<button v-else :type="type" :class="classes">
		<slot />
	</button>
</template>
