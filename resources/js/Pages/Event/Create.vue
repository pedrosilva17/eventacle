<script setup>
import DangerButton from '@/Components/DangerButton.vue';
import DateTimeInput from '@/Components/DateTimeInput.vue';
import FormSection from '@/Components/FormSection.vue';
import InputError from '@/Components/InputError.vue';
import InputGroup from '@/Components/InputGroup.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import SelectInput from '@/Components/SelectInput.vue';
import TextareaInput from '@/Components/TextareaInput.vue';
import TextInput from '@/Components/TextInput.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { useForm } from '@inertiajs/vue3';
import { reactive } from 'vue';

const form = useForm({
	name: '',
	description: '',
	start_time: '',
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

const addContest = (index) => {
	form.contests.splice(index + 1, 0, {
		name: '',
		description: '',
		options: ['', ''],
		optionIndices: [0, 1],
	});
};

const removeContest = (index) => {
	form.contests.splice(index, 1);
	form.errors = [];
};

const addOption = (index) => {
	form.contests[index].options.push('');
	form.contests[index].optionIndices.push(
		form.contests[index].optionIndices[form.contests[index].optionIndices.length - 1] + 1,
	);
};

const removeOption = (index, optionIndex) => {
	const options = form.contests[index].options;
	const optionIndices = form.contests[index].optionIndices;
	form.contests[index].options.splice(optionIndices.indexOf(optionIndex), 1);
	form.contests[index].optionIndices.splice(optionIndices.indexOf(optionIndex), 1);
};

const beforeLeave = (el) => {
	const { marginLeft, marginTop, width, height } = window.getComputedStyle(el);
	el.style.left = `${el.offsetLeft - parseFloat(marginLeft, 10)}px`;
	el.style.top = `${el.offsetTop - parseFloat(marginTop, 10)}px`;
	el.style.width = width;
	el.style.height = height;
};

function submit() {
	form.start_time = form.start_time + 'Z';
	form.post(route('event.new'));
}
</script>

<template>
	<AppLayout title="Create">
		<div class="mx-auto max-w-7xl py-10 sm:px-6 lg:px-8">
			<FormSection @submit.prevent="submit">
				<template #title>
					<h1>Create Event</h1>
				</template>
				<template #form>
					<InputGroup>
						<InputLabel for="name">Name</InputLabel>
						<TextInput v-model="form.name" type="text" id="name" />
						<InputError :message="form.errors.name" />
					</InputGroup>

					<InputGroup>
						<InputLabel for="description">Description</InputLabel>
						<TextareaInput v-model="form.description" id="description"></TextareaInput>
						<InputError :message="form.errors.description" />
					</InputGroup>

					<InputGroup>
						<InputLabel for="start_time">Start time (UTC Timezone)</InputLabel>
						<DateTimeInput v-model="form.start_time" id="start_time" />
						<InputError :message="form.errors.start_time" />
					</InputGroup>
					<TransitionGroup
						name="scale"
						tag="ul"
						class="my-8 grid md:col-span-2 md:grid-cols-subgrid md:gap-8"
						:duration="300"
					>
						<li
							v-for="(contest, index) in form.contests"
							:key="contest"
							class="grid md:col-span-2 md:grid-cols-subgrid md:gap-4"
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

							<TransitionGroup
								@before-leave="beforeLeave"
								name="slide-fade"
								tag="div"
								class="relative mt-8 flex flex-col max-md:mb-4 md:col-span-2 md:ml-auto md:mt-4 md:w-2/3 md:gap-2"
							>
								<span class="flex w-full flex-col items-end gap-2 md:col-start-2" key="add button">
									<SecondaryButton
										aria-label="Add option"
										class="w-fit gap-2 md:col-start-2"
										type="button"
										@click="addOption(index)"
										><i-ic-round-add class="text-lg"
									/></SecondaryButton>
								</span>
								<span
									v-for="(op, opIndex) in contest.options"
									:key="form.contests[index].optionIndices[opIndex]"
									class="flex justify-end"
								>
									<InputGroup class="w-full">
										<span
											class="flex flex-col items-end text-center md:flex-row md:items-center md:gap-4"
										>
											<InputLabel
												:for="`contest-option-${index}-${opIndex}`"
												class="w-full text-start md:w-48"
												>Contest Option</InputLabel
											>
											<TextInput
												v-model="contest.options[opIndex]"
												type="text"
												:id="`contest-option-${index}-${opIndex}`"
											/>
											<DangerButton
												aria-label="Remove option"
												:disabled="contest.options.length <= 2"
												class="w-fit items-end max-md:mt-2"
												type="button"
												@click="
													removeOption(index, form.contests[index].optionIndices[opIndex])
												"
											>
												<i-ic-round-delete class="text-lg" />
											</DangerButton>
										</span>
										<InputError :message="form.errors[`contests.${index}.options.${opIndex}`]" />
									</InputGroup>
								</span>
								<span
									class="mt-8 flex w-full flex-col items-end gap-4 md:col-start-2"
									key="contest buttons"
								>
									<DangerButton
										:disabled="form.contests.length <= 1"
										class="mt-4 w-fit gap-2 md:col-start-2"
										type="button"
										@click="removeContest(index)"
									>
										Remove Contest <i-ic-round-delete class="text-lg" />
									</DangerButton>
									<SecondaryButton type="button" @click="addContest(index)" class="w-fit gap-2"
										>Add Contest<i-ic-round-add class="text-lg"
									/></SecondaryButton>
								</span>
							</TransitionGroup>
						</li>
					</TransitionGroup>
					<span class="flex w-full flex-col items-end gap-4 md:col-start-2"> </span>

					<InputGroup>
						<InputLabel for="scoring_type">Scoring Type:</InputLabel>
						<SelectInput v-model="form.scoring_type" id="scoring_type" required>
							<option value="single points">Single Points</option>
							<option value="confidence points" selected>Confidence Points</option>
						</SelectInput>
						<Transition
							name="fade"
							mode="out-in"
							class="col-span-2 mt-2 w-full justify-start break-words text-end md:w-2/3"
						>
							<p v-if="form.scoring_type === 'single points'">
								Single point scoring is simple: every contest is worth a point.
							</p>
							<p v-else-if="form.scoring_type === 'confidence points'">
								Confidence point scoring prompts the user to order their predictions based on how
								confident they are to get each one right, from 1 (least confident) up to the number of
								contests (most confident), assigning that score to each prediction. For every correct
								prediction, their confidence score is added to the total. Useful to make ties less
								common.
							</p>
						</Transition>
					</InputGroup>
				</template>
				<template #actions>
					<PrimaryButton :disabled="form.processing">Create Event</PrimaryButton>
				</template>
			</FormSection>
		</div>
	</AppLayout>
</template>
