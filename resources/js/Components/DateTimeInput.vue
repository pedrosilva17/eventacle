<script setup>
import { onMounted, ref } from 'vue';

defineProps({
	modelValue: String,
});

defineEmits(['update:modelValue']);

const input = ref(null);

onMounted(() => {
	if (input.value.hasAttribute('autofocus')) {
		input.value.focus();
	}
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
	<input
		ref="input"
		class="mt-1 block rounded-md border-black-light bg-white-light text-black-dark shadow-sm transition duration-300 ease-in-out focus:border-primary-extradark focus:ring-primary-extradark dark:border-white-dark dark:bg-black-dark dark:text-white-light dark:focus:border-primary-extralight dark:focus:ring-primary-extralight"
		:value="modelValue"
		type="datetime-local"
		@input="$emit('update:modelValue', $event.target.value)"
	/>
</template>
