<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { convertUtcToLocalString } from '@/Lib/Utils';
import { useForm, usePage } from '@inertiajs/vue3';
import { reactive } from 'vue';
import EventForm from './Partials/EventForm.vue';
import FormTitle from '@/Components/FormTitle.vue';

const props = defineProps({
	users: {
		type: Array,
		default: [],
	},
});

const page = usePage();
const form = useForm({
	name: '',
	description: '',
	start_time: convertUtcToLocalString(new Date().toISOString()),
	user_timezone: Intl.DateTimeFormat().resolvedOptions().timeZone,
	creator_id: page.props.auth.user.id,
	contests: reactive([
		{
			name: '',
			description: '',
			options: ['', ''],
			optionIndices: [0, 1],
		},
	]),
	scoring_type: 'single points',
});

function submit() {
	form.post(route('event.new'));
}
</script>

<template>
	<AppLayout title="Create Event">
		<FormTitle title="Create Event" />
		<EventForm
			@submitted="submit"
			:form="form"
			button-text="Create Event"
			:creating-or-admin="true"
			:users="users"
		/>
	</AppLayout>
</template>
