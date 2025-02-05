<script setup>
import Button from './Button.vue';
import OutlineButton from './OutlineButton.vue';

const back = () => window.history.back();
</script>

<template>
	<div
		class="flex min-h-screen flex-col items-center bg-white-dark pt-6 sm:justify-center sm:pt-0 dark:bg-black-dark"
	>
		<div
			class="relative mt-6 w-full overflow-hidden bg-white px-6 py-4 shadow-md sm:max-w-md sm:rounded-lg dark:bg-black"
		>
			<OutlineButton @click="back">
				<i-ic-round-arrow-back class="text-lg" />
			</OutlineButton>
			<slot />
		</div>
	</div>
</template>
