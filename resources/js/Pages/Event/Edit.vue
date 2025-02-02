<script setup>
import FormSection from '@/Components/FormSection.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { useForm } from '@inertiajs/vue3';
import EventStartTimeField from './Partials/EventStartTimeField.vue';
import EventDescriptionField from './Partials/EventDescriptionField.vue';
import EventNameField from './Partials/EventNameField.vue';
import ContestNameField from './Partials/ContestNameField.vue';
import ContestDescriptionField from './Partials/ContestDescriptionField.vue';

const props = defineProps({
	event: {
		type: Object,
	},
});

const form = useForm({
	event: props.event,
	name: props.event.name,
	description: props.event.description,
	start_time: props.event.start_time.replace('Z', ''),
	contests: props.event.contests,
});

function submit() {
	form.start_time = form.start_time + 'Z';
	form.post(
		route('event.edit', {
			event: props.event,
		}),
	);
	form.start_time = form.start_time.slice(0, -1);
}
</script>

<template>
	<AppLayout title="Edit">
		<h1 class="flex w-full items-center pb-6 pt-1 text-center text-xl font-bold max-sm:px-4 sm:pt-2 sm:text-3xl">
			Edit - {{ form.event.name }}
		</h1>
		<FormSection @submit.prevent="submit">
			<template #form>
				<EventNameField v-model="form.name" :error="form.errors.name" disabled />
				<EventDescriptionField v-model="form.description" :error="form.errors.description" />
				<EventStartTimeField v-model="form.start_time" :error="form.errors.start_time" />

				<ul class="my-8 grid gap-16 md:col-span-2 md:grid-cols-subgrid">
					<li
						v-for="(contest, index) in form.contests"
						:key="contest"
						class="-mx-6 -my-4 grid gap-3 bg-white-light px-6 py-4 md:col-span-2 md:grid-cols-subgrid lg:rounded-lg dark:bg-black-light"
					>
						<h2 class="text-lg font-bold md:col-span-2">Contest {{ index + 1 }}</h2>
						<ContestNameField
							v-model="contest.name"
							:error="form.errors[`contests.${index}.name`]"
							:index="index"
						/>
						<ContestDescriptionField
							v-model="contest.description"
							:error="form.errors[`contests.${index}.description`]"
							:index="index"
						/>
					</li>
				</ul>
			</template>
			<template #actions>
				<PrimaryButton type="submit">Update Event</PrimaryButton>
			</template>
		</FormSection>
	</AppLayout>
</template>
