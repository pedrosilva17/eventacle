<script setup>
import { cn } from '@/Lib/Utils';
import { ChevronDown } from 'lucide-vue-next';
import { AccordionHeader, AccordionTrigger } from 'radix-vue';
import { computed } from 'vue';

const props = defineProps({
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
	<AccordionHeader class="flex">
		<AccordionTrigger
			v-bind="delegatedProps"
			:class="
				cn(
					'flex h-full flex-1 items-center justify-between rounded-lg px-6 py-4 font-medium transition focus:outline-none focus:ring focus:ring-primary dark:focus:ring-primary-extralight [&[data-state=open]>svg]:rotate-180',
					props.class,
				)
			"
		>
			<slot />
			<slot name="icon">
				<ChevronDown class="h-4 w-4 shrink-0 transition-transform duration-200" />
			</slot>
		</AccordionTrigger>
	</AccordionHeader>
</template>
