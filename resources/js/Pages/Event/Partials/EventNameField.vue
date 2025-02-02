<script setup lang="ts">
import InputError from '@/Components/InputError.vue';
import InputGroup from '@/Components/InputGroup.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';

defineProps({
	disabled: {
		type: Boolean,
	},
	error: {
		type: String,
	},
});

const fieldValue = defineModel({ type: String });
</script>

<template>
	<InputGroup>
		<InputLabel for="name">Name</InputLabel>
		<TextInput v-model="fieldValue" type="text" id="name" :disabled="disabled" />
		<InputError :message="error" />
	</InputGroup>
</template>
