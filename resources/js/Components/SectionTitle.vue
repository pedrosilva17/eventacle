<template>
	<div class="flex justify-between md:col-span-1">
		<div class="px-4 sm:px-0">
			<h3 class="text-lg font-medium text-black-dark dark:text-white-light">
				<slot name="title" />
			</h3>

			<p class="mt-1 text-sm text-black dark:text-white">
				<slot name="description" />
			</p>
		</div>

		<div class="px-4 sm:px-0">
			<slot name="aside" />
		</div>
	</div>
</template>
