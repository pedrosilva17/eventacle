<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import AdminCard from '@/Components/AdminCard.vue';
import DashboardSection from '@/Components/DashboardSection.vue';
import StatsCard from './Partials/StatsCard.vue';

defineProps({
	stats: {
		type: Object,
		required: true,
	},
});

const cards = [
	{ title: 'Users', route: route('admin.users') },
	{ title: 'Events', route: route('admin.events') },
];
</script>

<template>
	<AppLayout title="Admin Dashboard">
		<DashboardSection title="Admin Panel">
			<template #icon>
				<i-ic-round-dashboard aria-labelledby="admin-panel" />
			</template>
			<template #content>
				<div class="flex flex-col gap-3">
					<template v-for="card in cards" :key="card.title">
						<AdminCard :title="card.title" :route="card.route" />
					</template>
				</div>
			</template>
		</DashboardSection>

		<DashboardSection title="Website Statistics" class="mt-6">
			<template #icon>
				<i-ic-round-analytics aria-labelledby="website-stats" />
			</template>
			<template #content>
				<div class="flex flex-col gap-3">
					<StatsCard title="Events Created" :stats="stats.events" />
					<StatsCard title="Predictions Made" :stats="stats.predictions" />
				</div>
			</template>
		</DashboardSection>
	</AppLayout>
</template>
