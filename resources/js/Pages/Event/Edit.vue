<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { useForm } from '@inertiajs/vue3';
import { convertUtcToLocalString } from '@/Lib/Utils';
import EventForm from './Partials/EventForm.vue';
import FormTitle from '@/Components/FormTitle.vue';

const props = defineProps({
	event: {
		type: Object,
	},
	users: {
		type: Array,
		default: [],
	},
});

const form = useForm({
	event: props.event,
	name: props.event.name,
	description: props.event.description,
	creator_id: props.event.creator_id,
	start_time: convertUtcToLocalString(props.event.start_time),
	user_timezone: Intl.DateTimeFormat().resolvedOptions().timeZone,
	contests: props.event.contests.map((contest) => ({
		...contest,
		options: contest.options.split('|SEP|'),
		optionIndices: [...Array(contest.options.split('|SEP|').length).keys()],
	})),
	scoring_type: props.event.scoring_type,
});

function submit() {
	form.post(route('event.update', { event: props.event }));
}
</script>

<template>
	<AppLayout title="Edit Event">
		<FormTitle :title="`Edit - ${form.event.name}`" />
		<EventForm
			@submitted="submit"
			:form="form"
			button-text="Create Event"
			:creating-or-admin="$page.props.auth.user?.is_admin"
			:users="users"
		/>
	</AppLayout>
</template>
