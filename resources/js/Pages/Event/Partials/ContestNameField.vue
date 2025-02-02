<script setup lang="ts">
import InputError from '@/Components/InputError.vue';
import InputGroup from '@/Components/InputGroup.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';

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
		<InputLabel :for="'contest-name-' + index">Contest Name</InputLabel>
		<TextInput v-model="fieldValue" type="text" :id="'contest-name-' + index" />
		<InputError :message="error" />
	</InputGroup>
</template>
