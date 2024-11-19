<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
	active: Boolean,
	href: String,
	as: String,
});

const classes = computed(() => {
	return props.active
		? 'border-l-4 border-primary bg-primary bg-opacity-20 block px-4 py-2 text-black-dark dark:text-white-light hover:bg-white-light dark:hover:bg-black-light focus:outline-none focus:bg-white-light dark:focus:bg-black-light transition duration-300 ease-in-out'
		: 'block px-4 py-2 text-black-dark dark:text-white-light hover:bg-white-light dark:hover:bg-black-light focus:outline-none focus:bg-white-light dark:focus:bg-black-light transition duration-300 ease-in-out';
});
</script>

<template>
	<div>
		<button v-if="as == 'button'" :class="classes" class="w-full text-start">
			<slot />
		</button>

		<Link v-else :href="href" :class="classes">
			<slot />
		</Link>
	</div>
</template>
