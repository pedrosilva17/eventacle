<script setup>
import { Link } from '@inertiajs/vue3';

const props = defineProps({
	title: {
		type: String,
		required: true,
	},
	route: {
		type: String,
		required: true,
	},
});
</script>

<template>
	<div
		class="flex w-full rounded-lg bg-white-light shadow transition-colors hover:bg-primary-extralight dark:bg-black-light dark:hover:bg-primary-extradark"
	>
		<Link :href="route" class="w-full px-6 py-4 font-semibold">
			<p class="flex-1">
				{{ title }}
			</p>
		</Link>
	</div>
</template>
