<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import ManageResourceContainer from '@/Pages/Admin/Partials/ManageResourceContainer.vue';

const props = defineProps({
	events: {
		type: Object,
		required: true,
	},
});
</script>

<template>
	<AppLayout title="Manage Events">
		<ManageResourceContainer :items="events" resource="event" />
	</AppLayout>
</template>
