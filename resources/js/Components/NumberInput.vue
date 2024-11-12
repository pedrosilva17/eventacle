<script setup>
const props = defineProps({
	id: String,
	min: Number,
	max: Number,
	required: {
		type: Boolean,
		default: true,
	},
});

const buttonClasses = 'absolute flex justify-center px-1 w-4 right-1 text-xs bg-white dark:bg-black-light';
const modelValue = defineModel();
const update = (inc) => {
	return (modelValue.value = !modelValue.value
		? 1
		: Math.max(props.min, Math.min(modelValue.value + inc, props.max)));
};
</script>

<template>
	<span class="relative border-0 bg-transparent p-0">
		<input
			:id="id"
			:min="min"
			:max="max"
			type="number"
			class="peer block w-full rounded-md border-black-light bg-white-light text-black-dark transition duration-300 ease-in-out focus:border-primary-extradark focus:ring-primary-extradark dark:border-black-dark dark:border-white-dark dark:bg-black-dark dark:text-white-light dark:focus:border-primary-extralight dark:focus:ring-primary-extralight"
			v-model="modelValue"
			:required="required ?? null"
		/>
		<button
			type="button"
			@click="update(1)"
			aria-label="Increment value"
			:class="buttonClasses + ' top-1 rounded-t-sm peer-disabled:pointer-events-none peer-disabled:opacity-80'"
		>
			+
		</button>
		<button
			type="button"
			@click="update(-1)"
			aria-label="Decrement value"
			:class="buttonClasses + ' bottom-1 rounded-b-sm peer-disabled:pointer-events-none peer-disabled:opacity-80'"
		>
			-
		</button>
	</span>
</template>
