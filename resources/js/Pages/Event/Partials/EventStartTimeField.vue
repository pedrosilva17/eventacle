<script setup lang="ts">
import DateTimeInput from '@/Components/DateTimeInput.vue';
import InputError from '@/Components/InputError.vue';
import InputGroup from '@/Components/InputGroup.vue';
import InputLabel from '@/Components/InputLabel.vue';

defineProps({
	error: {
		type: String,
	},
});

const fieldValue = defineModel({ type: String });
</script>

<template>
	<InputGroup>
		<InputLabel for="start_time">Start time</InputLabel>
		<DateTimeInput v-model="fieldValue" id="start_time" />
		<InputError :message="error" />
		<p class="mt-1 flex text-xs text-black-light md:-mt-3 dark:text-white-dark">(Your timezone)</p>
	</InputGroup>
</template>
