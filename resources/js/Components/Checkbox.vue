<script setup>
import { computed } from 'vue';

const emit = defineEmits(['update:checked']);

const props = defineProps({
	checked: {
		type: [Array, Boolean],
		default: false,
	},
	value: {
		type: String,
		default: null,
	},
});

const proxyChecked = computed({
	get() {
		return props.checked;
	},

	set(val) {
		emit('update:checked', val);
	},
});
</script>

<template>
	<input
		v-model="proxyChecked"
		type="checkbox"
		:value="value"
		class="rounded border-black-light text-primary shadow-sm transition focus:ring-primary-extradark dark:border-white-dark dark:bg-black-dark dark:focus:ring-primary-extralight dark:focus:ring-offset-gray-800"
	/>
</template>
