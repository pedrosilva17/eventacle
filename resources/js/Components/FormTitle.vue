<script setup>
import { usePage } from '@inertiajs/vue3';

const props = defineProps({
	title: {
		type: String,
		required: true,
	},
});

const page = usePage();
const useAdminSuffix = page.props.auth.user?.is_admin && page.url.startsWith('/admin');
</script>

<template>
	<h1 class="flex w-full items-center pb-6 pt-1 text-center text-xl font-bold max-sm:px-4 sm:pt-2 sm:text-3xl">
		{{ title }} {{ useAdminSuffix ? '(Admin)' : '' }}
	</h1>
</template>
