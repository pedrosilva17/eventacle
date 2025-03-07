<script setup>
import { Link } from '@inertiajs/vue3';
import DangerButton from '@/Components/DangerButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';

const props = defineProps({
	identifier: {
		type: String,
		default: '',
	},
	item: {
		type: Object,
		required: true,
	},
	resource: {
		type: String,
		required: true,
	},
});

const emit = defineEmits(['delete']);
</script>

<template>
	<div
		class="flex flex-col items-center justify-between gap-2 rounded-lg bg-white-light px-6 py-4 sm:flex-row dark:bg-black-light"
	>
		<div class="flex-1">
			<template v-if="identifier">
				{{ identifier }}
			</template>
			<template v-else>
				<slot />
			</template>
		</div>
		<div class="flex justify-end gap-2 max-sm:w-full">
			<Link :href="route(`admin.${resource}.edit`, item)">
				<SecondaryButton>
					<i-ic-round-edit :aria-label="`edit ${resource}`" class="text-lg" />
				</SecondaryButton>
			</Link>
			<DangerButton @click="$emit('delete', item)">
				<i-ic-round-delete :aria-label="`delete ${resource}`" class="text-lg" />
			</DangerButton>
		</div>
	</div>
</template>
