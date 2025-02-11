<script setup>
import { computed, onMounted, onUnmounted, ref, Transition } from 'vue';

const props = defineProps({
	align: {
		type: String,
		default: 'right',
	},
	width: {
		type: String,
		default: '48',
	},
	contentClasses: {
		type: Array,
		default: () => ['py-1', 'bg-white dark:bg-black'],
	},
});

let open = defineModel();

const closeOnEscape = (e) => {
	if (open.value && e.key === 'Escape') {
		open.value = false;
	}
};

onMounted(() => document.addEventListener('keydown', closeOnEscape));
onUnmounted(() => document.removeEventListener('keydown', closeOnEscape));

const widthClass = computed(() => {
	return {
		28: 'w-28',
		48: 'w-48',
	}[props.width.toString()];
});

const alignmentClasses = computed(() => {
	if (props.align === 'left') {
		return 'ltr:origin-top-left rtl:origin-top-right start-0';
	}

	if (props.align === 'right') {
		return 'ltr:origin-top-right rtl:origin-top-left end-0';
	}

	return 'origin-top';
});

const toggleDropdown = () => {
	open.value = !open.value;
};
</script>

<template>
	<div class="relative">
		<div @click="toggleDropdown">
			<slot name="trigger" />
		</div>

		<!-- Full Screen Dropdown Overlay -->
		<div v-show="open" class="fixed inset-0 z-40" @click="open = false" />

		<Transition name="slide-fade">
			<div
				v-show="open"
				class="absolute z-50 mt-2 overflow-auto rounded-md shadow-lg"
				:class="[widthClass, alignmentClasses]"
				style="display: none"
				@click="open = false"
			>
				<div class="rounded-md ring-1 ring-black ring-opacity-5" :class="contentClasses">
					<slot name="content" />
				</div>
			</div>
		</Transition>
	</div>
</template>
