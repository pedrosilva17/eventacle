<script setup>
import { computed, useSlots } from 'vue';
import SectionTitle from './SectionTitle.vue';

defineEmits(['submitted']);

const hasActions = computed(() => !!useSlots().actions);
</script>

<template>
	<div class="flex flex-col gap-3">
		<SectionTitle v-if="!!$slots.title || !!$slots.description">
			<template #title>
				<slot name="title" />
			</template>
			<template #description>
				<slot name="description" />
			</template>
		</SectionTitle>

		<div class="mt-5 md:col-span-2 md:mt-0">
			<form @submit.prevent="$emit('submitted')">
				<div
					class="bg-white px-4 py-5 shadow sm:p-4 dark:bg-black"
					:class="hasActions ? 'sm:rounded-tl-xl sm:rounded-tr-xl' : 'sm:rounded-xl'"
				>
					<div class="m-auto grid max-w-screen-lg gap-4 md:grid-cols-auto">
						<slot name="form" />
					</div>
				</div>

				<div
					v-if="hasActions"
					class="flex items-center justify-end bg-white-light p-4 text-end shadow sm:rounded-bl-xl sm:rounded-br-xl dark:bg-black-light"
				>
					<span class="m-auto flex w-full max-w-screen-lg justify-end">
						<slot name="actions" />
					</span>
				</div>
			</form>
		</div>
	</div>
</template>
