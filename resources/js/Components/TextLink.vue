<script setup>
import { cn } from '@/Lib/Utils';
import { Link } from '@inertiajs/vue3';
import { defineProps } from 'vue';

defineProps({
	external: {
		type: Boolean,
		default: false,
	},
	href: {
		type: [String, Function],
		required: true,
	},
});

const linkClasses =
	'rounded-md text-sm text-black-light transition hover:text-primary-extradark focus:outline-none focus:ring-2 focus:ring-primary-extradark focus:ring-offset-2 focus:ring-offset-white dark:text-white-dark dark:hover:text-primary-extralight dark:focus:ring-primary-extralight dark:focus:ring-offset-black';
</script>

<template>
	<component :is="external ? 'a' : Link" :href="href" :class="cn(linkClasses, $attrs.class)">
		<slot />
	</component>
</template>
