<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { useForm } from '@inertiajs/vue3';
import UserForm from './Partials/UserForm.vue';
import FormTitle from '@/Components/FormTitle.vue';

const props = defineProps({
	user: {
		type: Object,
	},
});

const form = useForm({
	id: props.user?.id || -1,
	name: props.user?.name || '',
	email: props.user?.email || '',
	password: '',
	password_confirmation: '',
	is_admin: !!props.user?.is_admin || false,
});

function submit() {
	if (props.user) {
		form.post(route('admin.user.update', { user: props.user }));
	} else {
		form.post(route('admin.user.new'));
	}
}
</script>

<template>
	<AppLayout :title="user ? 'Edit User' : 'Create User'">
		<FormTitle :title="user ? `Edit - ${user.name}` : 'Create User'" />
		<UserForm
			@submitted="submit"
			:form="form"
			:button-text="user ? 'Update User' : 'Create User'"
			:creating="!user"
		/>
	</AppLayout>
</template>
