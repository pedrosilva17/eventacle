<script setup>
import { cn } from '@/Lib/Utils';
import { AccordionItem, useForwardProps } from 'radix-vue';
import { computed } from 'vue';

const props = defineProps({
	disabled: { type: Boolean, required: false },
	value: { type: String, required: true },
	asChild: { type: Boolean, required: false },
	as: { type: null, required: false },
	class: { type: null, required: false },
});

const delegatedProps = computed(() => {
	const { class: _, ...delegated } = props;

	return delegated;
});

const forwardedProps = useForwardProps(delegatedProps);
</script>

<template>
	<AccordionItem
		v-bind="forwardedProps"
		:class="cn('rounded-lg bg-white-light shadow dark:bg-black-light', props.class)"
	>
		<slot />
	</AccordionItem>
</template>
