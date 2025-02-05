<template>
	<section
		class="flex flex-col items-center gap-6 bg-gradient-to-b from-white-dark to-white-light px-6 py-16 shadow-md dark:from-black-dark dark:to-black"
	>
		<h2 class="text-2xl font-bold sm:text-4xl">
			<slot name="heading" />
		</h2>
		<slot name="content" />
	</section>
</template>
