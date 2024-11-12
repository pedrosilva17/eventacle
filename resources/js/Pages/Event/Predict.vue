<script setup>
import FormSection from '@/Components/FormSection.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import NumberInput from '@/Components/NumberInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import RadioInput from '@/Components/RadioInput.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
	event: {
		type: Object,
	},
	userId: {
		type: [Number, String, null],
	},
});

const userPredictions = {};
const userPoints = {};
if (props.userId) {
	props.event.predictions
		.filter((p) => {
			return p.user_id === props.userId;
		})
		.forEach((prediction) => {
			userPredictions[prediction.contest_id] = prediction.prediction_name;
			userPoints[prediction.contest_id] = prediction.points;
		});
}

const form = useForm({
	guest_user_name: '',
	predictions: userPredictions,
	points: userPoints,
	event: props.event,
});

function submit() {
	form.points = form.event.scoring_type === 'confidence points' ? form.points : {};
	form.post(
		route('event.predict', {
			event: props.event,
		}),
	);
}
</script>

<template>
	<AppLayout>
		<h1 class="flex w-full items-center pb-6 pt-1 text-center text-xl font-bold max-sm:px-4 sm:pt-2 sm:text-3xl">
			Predict - {{ event.name }}
		</h1>
		<FormSection @submit.prevent="submit">
			<template #form>
				<form
					@submit.prevent="submitForm"
					class="col-span-full m-auto grid w-full grid-cols-1 gap-3 md:grid-cols-2 xl:grid-cols-3"
				>
					<template v-for="contest in event.contests" :key="contest.id" class="flex-col">
						<span class="col-span-1 flex flex-col gap-3">
							<h2 class="break-words text-xl">{{ contest.name }}</h2>
							<span class="flex max-h-52 flex-1 flex-col gap-3 overflow-y-auto py-2">
								<div
									v-for="(option, index) in contest.options.split('|SEP|')"
									:key="index"
									class="pl-2"
								>
									<RadioInput
										:id="'prediction_' + contest.id + '_' + index"
										:name="'prediction_' + contest.id"
										:value="option"
										v-model="form.predictions[contest.id]"
										required
									>
										{{ option }}
									</RadioInput>
								</div>
							</span>
							<template v-if="event.scoring_type === 'confidence points'">
								<InputLabel :for="'points_' + contest.id">Confidence points</InputLabel>
								<NumberInput
									type="number"
									:id="'points_' + contest.id"
									:min="1"
									:max="event.contests.length"
									v-model="form.points[contest.id]"
									required
								/>
								<InputError :message="form.errors[`points.${contest.id}`]" class="text-red-500" />
							</template>
						</span>
					</template>

					<template v-if="!$page.props.auth.user">
						<label for="guest_user_name">Enter your name to make a prediction</label>
						<input
							v-model="form.guest_user_name"
							type="text"
							maxlength="255"
							id="guest_user_name"
							required
						/>
					</template>
					<Transition name="fade">
						<span v-if="form.errors['duplicate_name']" class="text-red-500">{{
							form.errors['duplicate_name']
						}}</span>
					</Transition>
				</form>
			</template>
			<template #actions>
				<PrimaryButton :disabled="form.processing" class="w-fit">Submit Prediction</PrimaryButton>
			</template>
		</FormSection>
	</AppLayout>
</template>
