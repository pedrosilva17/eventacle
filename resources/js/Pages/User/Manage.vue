<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import ManageResourceContainer from '@/Pages/Admin/Partials/ManageResourceContainer.vue';

const props = defineProps({
	users: {
		type: Object,
		required: true,
	},
});
</script>

<template>
	<AppLayout title="Manage Users">
		<ManageResourceContainer :items="users" resource="user" />
	</AppLayout>
</template>
