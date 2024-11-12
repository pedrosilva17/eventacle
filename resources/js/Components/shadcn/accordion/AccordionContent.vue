<script setup>
import { cn } from '@/Lib/Utils';
import { AccordionContent } from 'radix-vue';
import { computed } from 'vue';

const props = defineProps({
	forceMount: { type: Boolean, required: false },
	asChild: { type: Boolean, required: false },
	as: { type: null, required: false },
	class: { type: null, required: false },
});

const delegatedProps = computed(() => {
	const { class: _, ...delegated } = props;

	return delegated;
});
</script>

<template>
	<AccordionContent
		v-bind="delegatedProps"
		class="overflow-hidden text-sm transition-all data-[state=closed]:animate-accordion-up data-[state=open]:animate-accordion-down"
	>
		<div :class="cn('max-h-48 overflow-y-auto rounded-b-lg px-6 pb-4', props.class)">
			<slot />
		</div>
	</AccordionContent>
</template>
