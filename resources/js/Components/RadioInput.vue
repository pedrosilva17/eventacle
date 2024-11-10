<script setup>
defineProps({
	id: String,
	name: String,
	value: String,
});

const modelValue = defineModel();
</script>

<template>
	<span>
		<input type="radio" :id="id" :name="name" v-model="modelValue" :value="value" class="peer absolute -z-10" />
		<label
			:for="id"
			class="flex w-fit rounded-lg bg-white-light px-3 py-1 transition-all hover:translate-x-2 hover:bg-primary-extradark hover:text-white-light peer-checked:translate-x-2 peer-checked:bg-primary peer-checked:text-white-light peer-focus:translate-x-2 peer-focus:ring peer-focus:ring-primary peer-focus:ring-offset-2 peer-focus:ring-offset-white dark:bg-black-light peer-focus:dark:ring-offset-black"
		>
			<slot />
		</label>
	</span>
</template>
