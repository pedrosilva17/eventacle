<script setup lang="ts">
import InputError from '@/Components/InputError.vue';
import InputGroup from '@/Components/InputGroup.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextareaInput from '@/Components/TextareaInput.vue';
defineProps({
	index: {
		type: Number,
	},
	error: {
		type: String,
	},
});

const fieldValue = defineModel({ type: String });
</script>

<template>
	<InputGroup>
		<InputLabel :for="'contest-description-' + index">Contest Description</InputLabel>
		<TextareaInput v-model="fieldValue" :id="'contest-description-' + index"></TextareaInput>
		<InputError :message="error" />
	</InputGroup>
</template>
