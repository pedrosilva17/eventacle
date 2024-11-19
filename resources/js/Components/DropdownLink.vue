<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
	href: String,
	as: String,
});

const classes =
	'block px-4 py-2 text-sm leading-5 text-black-dark dark:text-white-light hover:bg-white-light dark:hover:bg-black-light focus:outline-none focus:bg-white-light dark:focus:bg-black-light transition duration-300 ease-in-out';
</script>

<template>
	<div>
		<button v-if="as == 'button'" type="submit" class="w-full text-start" :class="classes">
			<slot />
		</button>

		<Link v-else :href="href" :class="classes">
			<slot />
		</Link>
	</div>
</template>
