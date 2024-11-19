<script setup>
import DialogModal from '@/Components/DialogModal.vue';
import FormSection from '@/Components/FormSection.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import RadioInput from '@/Components/RadioInput.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
	event: {
		type: Object,
	},
});

const form = useForm({
	results: {},
	event: props.event,
});

const show = ref(false);

function submit() {
	show.value = false;
	form.post(
		route('event.publish-winners', {
			event: props.event,
		}),
	);
}
</script>

<template>
	<AppLayout>
		<h1 class="flex w-full items-center pb-6 pt-1 text-center text-xl font-bold max-sm:px-4 sm:pt-2 sm:text-3xl">
			Winners - {{ event.name }}
		</h1>
		<FormSection @submit.prevent="submit">
			<template #form>
				<div class="col-span-full m-auto grid w-full grid-cols-1 gap-3 md:grid-cols-2 xl:grid-cols-3">
					<template v-for="contest in event.contests" :key="contest.id" class="flex-col">
						<div class="col-span-1 flex flex-col gap-3">
							<h2 class="break-words text-xl">{{ contest.name }}</h2>
							<div class="flex max-h-52 flex-1 flex-col gap-3 overflow-y-auto py-2">
								<div v-for="(option, index) in contest.options.split('|SEP|')" :key="index">
									<RadioInput
										:id="'winner_' + contest.id + '_' + index"
										:name="'winner_' + contest.id"
										:value="option"
										v-model="form.results[contest.id]"
										:required="true"
									>
										{{ option }}
									</RadioInput>
								</div>
							</div>
							<InputError :message="form.errors['results'] || form.errors['size']" />
						</div>
					</template>
				</div>
			</template>
			<template #actions>
				<PrimaryButton type="button" @click="show = true" class="w-fit">Publish Winners</PrimaryButton>
			</template>
		</FormSection>
		<DialogModal :show="show" @close="show = false">
			<template #title>Publish Event Winners</template>
			<template #content>
				Are you sure these were the winners of the event? Once they are published, they cannot be changed.
			</template>
			<template #footer>
				<span class="flex gap-3">
					<SecondaryButton @click="show = false">Cancel</SecondaryButton>
					<PrimaryButton @click="submit">Publish</PrimaryButton>
				</span>
			</template>
		</DialogModal>
	</AppLayout>
</template>
