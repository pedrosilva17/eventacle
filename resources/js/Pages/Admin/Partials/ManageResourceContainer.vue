<script setup>
import { Link, router } from '@inertiajs/vue3';
import Container from '@/Components/Container.vue';
import AdminListItem from '@/Components/AdminListItem.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import DialogModal from '@/Components/DialogModal.vue';
import { ref } from 'vue';
import { convertUtcToLocalString } from '@/Lib/Utils';

const props = defineProps({
	items: {
		type: Object,
		required: true,
	},
	resource: {
		type: String,
		required: true,
	},
	title: {
		type: String,
		default: '',
	},
});

const identifier = (resource, item) => {
	switch (resource) {
		case 'user':
			return `${item['name']} - ${item['email']}`;
		case 'event':
			return `${item['name']} - ${item['creator']['name']} - ${convertUtcToLocalString(new Date(item['start_time']).toISOString()).replace('T', ' ')}`;
		default:
			return `${item['id']}`;
	}
};

const show = ref(false);
const activeItem = ref(null);

const displayPage = (page) => {
	if (props.items.current_page <= 3) {
		return page <= 5;
	}
	if (props.items.current_page >= props.items.last_page - 2) {
		return page > props.items.last_page - 5;
	}
	return Math.abs(props.items.current_page - page) <= 2;
};

const displayDots = (page) => {
	if (props.items.current_page <= 3) {
		return page === 6;
	}
	if (props.items.current_page >= props.items.last_page - 2) {
		return page === props.items.last_page - 5;
	}
	return page === props.items.current_page - 3 || page === props.items.current_page + 3;
};

const deleteRow = () => {
	show.value = false;
	router.delete(route(`admin.${props.resource}.delete`, { [props.resource]: activeItem.value }));
	activeItem.value = null;
};
</script>

<template>
	<Container>
		<div class="flex h-[calc(100vh-346px)] min-h-64 w-full flex-col gap-4 overflow-hidden p-4">
			<div class="flex items-center justify-between">
				<h1 class="text-lg font-bold sm:text-2xl">
					Manage {{ title ? title : resource.charAt(0).toUpperCase() + resource.slice(1) + 's' }}
				</h1>
				<Link :href="route(`admin.${resource}.create`)">
					<PrimaryButton>
						<i-ic-round-add :aria-label="`create ${resource}`" class="text-lg" />
					</PrimaryButton>
				</Link>
			</div>

			<div class="flex flex-1 flex-col gap-2 overflow-y-auto">
				<AdminListItem
					v-for="item in items.data"
					:key="item.id"
					:item="item"
					:identifier="identifier(resource, item)"
					:resource="resource"
					@delete="
						activeItem = item;
						show = true;
					"
				/>
			</div>

			<div
				class="mt-4 flex flex-col items-center justify-between gap-3 border-t border-white-dark pt-4 sm:flex-row dark:border-black-light"
			>
				<div class="flex items-center gap-2">
					<p class="text-sm text-black-light dark:text-white-dark">
						Showing {{ items.from }}-{{ items.to }} of {{ items.total }}
					</p>
				</div>
				<div class="flex gap-2">
					<Link
						id="first-page"
						:href="items.first_page_url"
						aria-label="First Page"
						:class="[
							'rounded-md px-2 py-1 text-sm transition',
							'bg-white-light enabled:hover:bg-white-dark dark:bg-black-light enabled:dark:hover:bg-black-dark',
							items.current_page === 1 && 'cursor-not-allowed opacity-50',
						]"
						:disabled="items.current_page === 1"
					>
						<i-ic-round-first-page aria-labelledby="first-page" class="text-lg" />
					</Link>
					<template v-for="page in items.last_page" :key="page">
						<Link
							v-if="displayPage(page)"
							:href="items.path + '?page=' + page"
							:class="[
								'min-w-8 rounded-md px-2 py-1 text-center text-sm transition',
								items.current_page === page
									? 'bg-primary-extralight text-black-dark dark:bg-primary-extradark dark:text-white-light'
									: 'bg-white-light hover:bg-white-dark dark:bg-black-light dark:hover:bg-black-dark',
							]"
						>
							{{ page }}
						</Link>
						<span
							v-else-if="displayDots(page)"
							class="px-2 text-black-light max-sm:hidden dark:text-white-dark"
						>
							...
						</span>
					</template>
					<Link
						id="last-page"
						:href="items.last_page_url"
						aria-label="Last Page"
						:class="[
							'rounded-md px-2 py-1 text-sm transition',
							'bg-white-light enabled:hover:bg-white-dark dark:bg-black-light enabled:dark:hover:bg-black-dark',
							items.current_page === items.last_page && 'cursor-not-allowed opacity-50',
						]"
						:disabled="items.current_page === items.last_page"
					>
						<i-ic-round-last-page aria-labelledby="last-page" class="text-lg" />
					</Link>
				</div>
			</div>
		</div>

		<DialogModal :show="show" @close="show = false">
			<template #title>Delete {{ resource.charAt(0).toUpperCase() + resource.slice(1) }}</template>
			<template #content> Are you sure you want to delete this? </template>
			<template #footer>
				<div class="flex gap-3">
					<SecondaryButton @click="show = false">Cancel</SecondaryButton>
					<DangerButton @click="deleteRow">Delete</DangerButton>
				</div>
			</template>
		</DialogModal>
	</Container>
</template>
