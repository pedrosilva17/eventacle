<script setup>
import DangerButton from '@/Components/DangerButton.vue';
import FormSection from '@/Components/FormSection.vue';
import InputError from '@/Components/InputError.vue';
import InputGroup from '@/Components/InputGroup.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import SelectInput from '@/Components/SelectInput.vue';
import TextInput from '@/Components/TextInput.vue';
import { beforeLeave, CONFIDENCE_POINTS_HELP, SINGLE_POINTS_HELP } from '@/Lib/Utils';
import EventStartTimeField from './EventStartTimeField.vue';
import EventDescriptionField from './EventDescriptionField.vue';
import EventNameField from './EventNameField.vue';
import ContestNameField from './ContestNameField.vue';
import ContestDescriptionField from './ContestDescriptionField.vue';

const props = defineProps({
	form: {
		type: Object,
		required: true,
	},
	buttonText: {
		type: String,
		required: true,
	},
	creatingOrAdmin: {
		type: Boolean,
		default: false,
	},
	users: {
		type: Array,
		default: [],
	},
});

const addContest = (index) => {
	props.form.contests.splice(index + 1, 0, {
		name: '',
		description: '',
		options: ['', ''],
		optionIndices: [0, 1],
	});
};

const removeContest = (index) => {
	props.form.contests.splice(index, 1);
	props.form.errors = [];
};

const addOption = (index) => {
	props.form.contests[index].options.push('');
	props.form.contests[index].optionIndices.push(
		props.form.contests[index].optionIndices[props.form.contests[index].optionIndices.length - 1] + 1,
	);
};

const removeOption = (index, optionIndex) => {
	const optionIndices = props.form.contests[index].optionIndices;
	props.form.contests[index].options.splice(optionIndices.indexOf(optionIndex), 1);
	props.form.contests[index].optionIndices.splice(optionIndices.indexOf(optionIndex), 1);
};

defineEmits(['submitted']);
</script>

<template>
	<FormSection @submit.prevent="$emit('submitted')">
		<template #form>
			<input type="hidden" v-model="form.user_timezone" />
			<EventNameField v-if="creatingOrAdmin" v-model="form.name" :error="form.errors.name" />
			<EventDescriptionField v-model="form.description" :error="form.errors.description" />
			<EventStartTimeField v-model="form.start_time" :error="form.errors.start_time" />

			<TransitionGroup
				name="scale"
				tag="ul"
				class="my-8 grid md:col-span-2 md:grid-cols-subgrid md:gap-16"
				:duration="300"
			>
				<li
					v-for="(contest, index) in form.contests"
					:key="contest"
					class="-mx-6 -my-4 grid gap-3 bg-white-light px-6 py-4 md:col-span-2 md:grid-cols-subgrid lg:rounded-lg dark:bg-black-light"
				>
					<h2 class="text-end text-lg font-bold md:col-span-2">Contest {{ index + 1 }}</h2>
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

					<TransitionGroup
						v-if="creatingOrAdmin"
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
							<InputGroup class="w-full md:flex md:flex-col md:items-end">
								<span class="flex flex-col items-end text-center md:flex-row md:items-center md:gap-3">
									<InputLabel :for="`contest-option-${index}-${opIndex}`" class="w-full text-start"
										>Contest Option</InputLabel
									>
									<TextInput
										v-model="contest.options[opIndex]"
										type="text"
										:id="`contest-option-${index}-${opIndex}`"
										class="md:w-64"
									/>
									<DangerButton
										aria-label="Remove option"
										:disabled="contest.options.length <= 2"
										class="w-fit items-end max-md:mt-2"
										type="button"
										@click="removeOption(index, form.contests[index].optionIndices[opIndex])"
									>
										<i-ic-round-delete class="text-lg" />
									</DangerButton>
								</span>
								<InputError
									class="text-right"
									:message="form.errors[`contests.${index}.options.${opIndex}`]"
								/>
							</InputGroup>
						</span>
					</TransitionGroup>
					<span
						v-if="creatingOrAdmin"
						class="mt-2 flex flex-col items-end justify-end gap-8 md:col-start-2"
						key="contest buttons"
					>
						<DangerButton
							:disabled="form.contests.length <= 1"
							class="w-fit gap-2 md:col-start-2"
							type="button"
							@click="removeContest(index)"
						>
							Remove Contest {{ index + 1 }}<i-ic-round-delete class="text-lg" />
						</DangerButton>
						<SecondaryButton type="button" @click="addContest(index)" class="w-fit gap-2"
							>Add Contest<i-ic-round-add class="text-lg"
						/></SecondaryButton>
					</span>
				</li>
			</TransitionGroup>

			<InputGroup v-if="creatingOrAdmin">
				<InputLabel for="scoring_type">Scoring Type</InputLabel>
				<SelectInput v-model="form.scoring_type" id="scoring_type" required>
					<option value="single points">Single Points</option>
					<option value="confidence points" selected>Confidence Points</option>
				</SelectInput>
				<Transition
					name="fade"
					mode="out-in"
					class="col-span-2 row-start-3 mt-2 w-full justify-start break-words text-end md:w-2/3"
				>
					<p v-if="form.scoring_type === 'single points'">
						{{ SINGLE_POINTS_HELP }}
					</p>
					<p v-else-if="form.scoring_type === 'confidence points'">
						{{ CONFIDENCE_POINTS_HELP }}
					</p>
				</Transition>
			</InputGroup>

			<InputGroup v-if="creatingOrAdmin && users.length > 0">
				<InputLabel for="creator_id">Creator</InputLabel>
				<SelectInput v-model="form.creator_id" id="creator_id" required>
					<option v-for="user in users" :key="user.id" :value="user.id">{{ user.name }}</option>
				</SelectInput>
			</InputGroup>
		</template>
		<template #actions>
			<PrimaryButton :disabled="form.processing">{{ buttonText }}</PrimaryButton>
		</template>
	</FormSection>
</template>
