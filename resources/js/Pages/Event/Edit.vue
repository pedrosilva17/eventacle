<script setup>
import DateTimeInput from '@/Components/DateTimeInput.vue';
import FormSection from '@/Components/FormSection.vue';
import InputError from '@/Components/InputError.vue';
import InputGroup from '@/Components/InputGroup.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextareaInput from '@/Components/TextareaInput.vue';
import TextInput from '@/Components/TextInput.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { useForm } from '@inertiajs/vue3';

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
}
</script>

<template>
	<AppLayout title="Edit">
		<h1 class="flex w-full items-center pb-6 pt-1 text-center text-xl font-bold max-sm:px-4 sm:pt-2 sm:text-3xl">
			Edit - {{ form.event.name }}
		</h1>
		<FormSection @submit.prevent="submit">
			<template #form>
				<InputGroup>
					<InputLabel for="name">Name</InputLabel>
					<TextInput v-model="form.name" type="text" id="name" disabled />
					<InputError :message="form.errors.name" />
				</InputGroup>

				<InputGroup>
					<InputLabel for="description">Description</InputLabel>
					<TextareaInput v-model="form.description" id="description"></TextareaInput>
					<InputError :message="form.errors.description" />
				</InputGroup>

				<InputGroup>
					<InputLabel for="start_time">Start time</InputLabel>
					<DateTimeInput v-model="form.start_time" id="start_time" />
					<InputError :message="form.errors.start_time" />
					<p
						class="absolute left-[68px] text-xs text-black-light max-md:top-[3px] md:-bottom-1 md:left-[100px] dark:text-white-dark"
					>
						(UTC)
					</p>
				</InputGroup>

				<ul class="my-8 grid md:col-span-2 md:grid-cols-subgrid md:gap-8">
					<li
						v-for="(contest, index) in form.contests"
						:key="contest"
						class="grid md:col-span-2 md:grid-cols-subgrid md:gap-3"
					>
						<h2 class="text-end text-lg font-bold md:col-span-2">Contest {{ index + 1 }}</h2>
						<InputGroup>
							<InputLabel :for="'contest-name-' + index">Contest Name</InputLabel>
							<TextInput v-model="contest.name" type="text" :id="'contest-name-' + index" />
							<InputError :message="form.errors[`contests.${index}.name`]" />
						</InputGroup>

						<InputGroup>
							<InputLabel :for="'contest-description-' + index">Contest Description</InputLabel>
							<TextareaInput
								v-model="contest.description"
								:id="'contest-description-' + index"
							></TextareaInput>
							<InputError :message="form.errors[`contests.${index}.description`]" />
						</InputGroup>
					</li>
				</ul>
			</template>
			<template #actions>
				<PrimaryButton type="submit">Update Event</PrimaryButton>
			</template>
		</FormSection>
	</AppLayout>
</template>
