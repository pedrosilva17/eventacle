<script setup>
import DangerButton from '@/Components/DangerButton.vue';
import DialogModal from '@/Components/DialogModal.vue';
import FormSection from '@/Components/FormSection.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import NumberInput from '@/Components/NumberInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import RadioInput from '@/Components/RadioInput.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { CONFIDENCE_POINTS_HELP, SINGLE_POINTS_HELP } from '@/Lib/Utils';
import { router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
	event: {
		type: Object,
	},
	userId: {
		type: [Number, String, null],
	},
});

const show = ref(false);

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

const deletePredictions = () => {
	router.delete(
		route('event.delete-user-predictions', {
			event: props.event,
			userId: props.userId,
		}),
	);
};

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
	<AppLayout title="Predict">
		<h1 class="flex w-full items-center pb-6 pt-1 text-center text-xl font-bold max-sm:px-4 sm:pt-2 sm:text-3xl">
			Predict - {{ event.name }}
		</h1>
		<FormSection @submit.prevent="submit">
			<template #description>
				<p v-if="($page, props.event.scoring_type === 'single points')">
					{{ SINGLE_POINTS_HELP }}
				</p>
				<p v-else-if="($page, props.event.scoring_type === 'confidence points')">
					{{ CONFIDENCE_POINTS_HELP }}
				</p>
			</template>
			<template #form>
				<div class="col-span-full m-auto grid w-full grid-cols-1 gap-3 md:grid-cols-2 xl:grid-cols-3">
					<template v-for="contest in event.contests" :key="contest.id" class="flex-col">
						<span class="col-span-1 flex flex-col gap-3">
							<h2 class="break-words text-xl">{{ contest.name }}</h2>
							<span class="flex max-h-52 flex-1 flex-col gap-3 overflow-y-auto py-2">
								<div v-for="(option, index) in contest.options.split('|SEP|')" :key="index">
									<RadioInput
										:id="'prediction_' + contest.id + '_' + index"
										:name="'prediction_' + contest.id"
										:value="option"
										v-model="form.predictions[contest.id]"
										:required="true"
									>
										{{ option }}
									</RadioInput>
								</div>
							</span>
							<InputError :message="form.errors['predictions']" />
							<div
								v-if="event.scoring_type === 'confidence points'"
								class="flex min-h-28 w-full flex-col"
							>
								<InputLabel :for="'points_' + contest.id">Confidence points</InputLabel>
								<NumberInput
									:id="'points_' + contest.id"
									:min="1"
									:max="event.contests.length"
									v-model="form.points[contest.id]"
									:required="true"
								/>
								<InputError :message="form.errors[`points.${contest.id}`] ?? form.errors['points']" />
							</div>
						</span>
					</template>
					<span v-if="!$page.props.auth.user" class="col-span-2 flex flex-col">
						<p class="col-span-2 mb-2 mt-6 text-sm">
							Don't have an account? No problem! Type a username below to identify your prediction.
						</p>
						<InputLabel :for="'guest_user_name'" class="mb-1 w-full text-left">Username</InputLabel>
						<TextInput v-model="form.guest_user_name" type="text" id="guest_user_name" required />
						<InputError :message="form.errors['duplicate_name']" />
					</span>
				</div>
			</template>
			<template #actions>
				<PrimaryButton :disabled="form.processing" class="w-fit">Submit Prediction</PrimaryButton>
				<DangerButton
					v-if="$page.props.auth.user && Object.keys(userPredictions).length !== 0"
					@click="show = !show"
					class="ml-3"
				>
					<i-ic-round-delete aria-label="Delete event" class="text-lg" />
				</DangerButton>
			</template>
		</FormSection>
		<DialogModal :show="show" @close="show = false">
			<template #title>Delete Event</template>
			<template #content> Are you sure you want to delete your predictions? </template>
			<template #footer>
				<div class="flex gap-3">
					<SecondaryButton @click="show = false">Cancel</SecondaryButton>
					<DangerButton @click="deletePredictions"> Delete </DangerButton>
				</div>
			</template>
		</DialogModal>
	</AppLayout>
</template>
