<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import DashboardSection from '@/Components/DashboardSection.vue';
import SmallEventCard from '@/Components/SmallEventCard.vue';
import BigEventCard from '@/Components/BigEventCard.vue';

const props = defineProps({
	eventsCreated: {
		type: Object,
	},
	eventsPredicted: {
		type: Object,
	},
	userPredictions: {
		type: Object,
	},
});
</script>

<template>
	<AppLayout title="Dashboard">
		<DashboardSection title="Your upcoming events">
			<template #icon>
				<i-ic-round-event aria-labelledby="your-upcoming-events" />
			</template>
			<template #content>
				<p v-if="eventsCreated.length === 0">No upcoming events created yet. Time to change that!</p>
				<div v-else class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3">
					<template v-for="event in eventsCreated">
						<SmallEventCard :event="event" />
					</template>
				</div>
			</template>
		</DashboardSection>
		<DashboardSection title="Your current predictions">
			<template #icon>
				<i-ic-round-auto-awesome aria-labelledby="your-current-predictions" />
			</template>
			<template #content>
				<p v-if="eventsPredicted.length === 0" class="text-center">
					You have no predictions on upcoming events. Try your luck!
				</p>
				<div v-else class="flex flex-col gap-3">
					<template v-for="event in eventsPredicted">
						<BigEventCard :event="event" :predictions="userPredictions[event.id]" />
					</template>
				</div>
			</template>
		</DashboardSection>
	</AppLayout>
</template>
