<script setup>
import { Link } from '@inertiajs/vue3';

const props = defineProps({
	href: String,
	active: Boolean,
});
</script>

<template>
	<Link
		:href="href"
		class="group relative inline-flex items-center overflow-hidden px-1 text-sm font-medium transition duration-150 ease-in-out hover:text-primary-extradark focus:outline-none dark:hover:text-primary-extralight"
	>
		<slot />
		<span
			class="absolute bottom-0 h-0 border-b-2 border-primary transition-all duration-200 ease-in-out group-hover:left-0 group-hover:w-full group-focus:left-0 group-focus:w-full"
			:class="props.active ? 'left-0 w-full' : 'left-1/2 w-0'"
		></span>
	</Link>
</template>
