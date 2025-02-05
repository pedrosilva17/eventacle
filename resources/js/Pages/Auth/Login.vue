<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';

defineProps({
	canResetPassword: Boolean,
	status: String,
});

const form = useForm({
	email: '',
	password: '',
	remember: false,
});

const submit = () => {
	form.transform((data) => ({
		...data,
		remember: form.remember ? 'on' : '',
	})).post(route('login'), {
		onFinish: () => form.reset('password'),
	});
};
</script>

<template>
	<Head title="Log in" />

	<AuthenticationCard>
		<div v-if="status" class="mb-4 text-sm font-medium text-green-600 dark:text-green-400">
			{{ status }}
		</div>

		<form @submit.prevent="submit">
			<ApplicationLogo class="m-auto w-48" />
			<h1
				class="mb-8 mt-4 flex w-full justify-center text-2xl font-bold text-black-dark sm:text-4xl dark:text-white-light"
			>
				Welcome back!
			</h1>
			<div>
				<InputLabel class="sr-only" for="email" value="Email" />
				<TextInput
					id="email"
					v-model="form.email"
					type="email"
					required
					autofocus
					autocomplete="email"
					placeholder="Email"
				/>
				<InputError class="mt-2" :message="form.errors.email" />
			</div>

			<div class="mt-4">
				<InputLabel class="sr-only" for="password" value="Password" />
				<TextInput
					id="password"
					v-model="form.password"
					type="password"
					required
					autocomplete="current-password"
					placeholder="Password"
				/>
				<InputError class="mt-2" :message="form.errors.password" />
			</div>

			<div class="mt-4 block">
				<label class="flex items-center">
					<Checkbox v-model:checked="form.remember" name="remember" />
					<span class="ms-2 text-sm text-black-light dark:text-white-dark">Remember me</span>
				</label>
			</div>

			<div class="mt-4 flex items-center justify-end">
				<Link
					:href="route('register')"
					class="rounded-md text-sm text-black-light underline transition hover:text-primary-extradark focus:outline-none focus:ring-2 focus:ring-primary-extradark focus:ring-offset-2 focus:ring-offset-white dark:text-white-dark dark:hover:text-primary-extralight dark:focus:ring-primary-extralight dark:focus:ring-offset-black"
				>
					Don't have an account?
				</Link>

				<PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
					Log in
				</PrimaryButton>
			</div>
		</form>
	</AuthenticationCard>
</template>
