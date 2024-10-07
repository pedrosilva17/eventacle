<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import ApplicationMark from '@/Components/ApplicationMark.vue';
import Banner from '@/Components/Banner.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

defineProps({
	title: String,
});

const showingProfileDropdown = ref(false);
const showingNavigationDropdown = ref(false);

const logout = () => {
	router.post(route('logout'));
};
</script>

<template>
	<div>
		<Head :title="title" />
		<Banner />
		<div class="min-h-screen bg-white-dark dark:bg-black-dark">
			<nav class="bg-white text-black-dark dark:bg-black dark:text-white-light">
				<!-- Primary Navigation Menu -->
				<div class="mx-auto flex h-16 max-w-7xl justify-between px-4 sm:px-6 lg:px-8">
					<!-- Logo -->
					<div class="flex shrink-0 items-center">
						<Link :href="route('dashboard')">
							<p
								class="block h-auto w-auto font-serif text-3xl font-bold text-primary dark:text-primary-extralight"
							>
								Eventacle
							</p>
						</Link>
					</div>

					<!-- Navigation Links -->
					<div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex sm:flex-1 sm:justify-end">
						<NavLink :href="route('dashboard')" :active="route().current('dashboard')"> Dashboard </NavLink>
						<NavLink :href="route('event.create')" :active="route().current('event.create')">
							Create Event
						</NavLink>
					</div>

					<div class="hidden sm:ms-6 sm:flex sm:items-center">
						<!-- Settings Dropdown -->
						<div class="relative ms-3">
							<Dropdown align="right" width="48" v-model="showingProfileDropdown">
								<template #trigger>
									<button
										v-if="$page.props.jetstream.managesProfilePhotos"
										class="flex rounded-full border-2 border-transparent text-sm transition focus:border-gray-300 focus:outline-none"
									>
										<img
											class="h-8 w-8 rounded-full object-cover"
											:src="$page.props.auth.user.profile_photo_url"
											:alt="$page.props.auth.user.name"
										/>
									</button>

									<PrimaryButton v-else type="button" aria-label="Open account dropdown">
										<i-ic-round-person class="text-xl" aria-label="Account" />
									</PrimaryButton>
								</template>

								<template #content>
									<!-- Account Management -->
									<div class="block px-4 py-2 text-xs text-gray-400">Manage Account</div>

									<DropdownLink :href="route('profile.show')"> Profile </DropdownLink>

									<div class="border-t border-gray-200 dark:border-gray-600" />

									<!-- Authentication -->
									<form @submit.prevent="logout">
										<DropdownLink as="button"> Log Out </DropdownLink>
									</form>
								</template>
							</Dropdown>
						</div>
					</div>

					<!-- Hamburger -->
					<div class="-me-2 flex items-center sm:hidden">
						<PrimaryButton
							aria-label="Open Navigation Menu"
							@click="showingNavigationDropdown = !showingNavigationDropdown"
						>
							<Transition name="scale" mode="out-in" class="text-2xl">
								<i-ic-round-menu v-if="!showingNavigationDropdown" />
								<i-ic-round-close v-else />
							</Transition>
						</PrimaryButton>
					</div>
				</div>

				<!-- Responsive Navigation Menu -->
				<div
					:class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }"
					class="sm:hidden"
				>
					<div class="space-y-1 pb-3 pt-2">
						<ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')">
							Dashboard
						</ResponsiveNavLink>
					</div>

					<!-- Responsive Settings Options -->
					<div class="border-t border-gray-200 pb-1 pt-4 dark:border-gray-600">
						<div class="flex items-center px-4">
							<div v-if="$page.props.jetstream.managesProfilePhotos" class="me-3 shrink-0">
								<img
									class="h-10 w-10 rounded-full object-cover"
									:src="$page.props.auth.user.profile_photo_url"
									:alt="$page.props.auth.user.name"
								/>
							</div>

							<div>
								<div class="text-base font-medium text-gray-800 dark:text-gray-200">
									{{ $page.props.auth.user.name }}
								</div>
								<div class="text-sm font-medium text-gray-500">
									{{ $page.props.auth.user.email }}
								</div>
							</div>
						</div>

						<div class="mt-3 space-y-1">
							<ResponsiveNavLink :href="route('profile.show')" :active="route().current('profile.show')">
								Profile
							</ResponsiveNavLink>

							<ResponsiveNavLink
								v-if="$page.props.jetstream.hasApiFeatures"
								:href="route('api-tokens.index')"
								:active="route().current('api-tokens.index')"
							>
								API Tokens
							</ResponsiveNavLink>

							<!-- Authentication -->
							<form method="POST" @submit.prevent="logout">
								<ResponsiveNavLink as="button"> Log Out </ResponsiveNavLink>
							</form>

							<!-- Team Management -->
							<template v-if="$page.props.jetstream.hasTeamFeatures">
								<div class="border-t border-gray-200 dark:border-gray-600" />

								<div class="block px-4 py-2 text-xs text-gray-400">Manage Team</div>

								<!-- Team Settings -->
								<ResponsiveNavLink
									:href="route('teams.show', $page.props.auth.user.current_team)"
									:active="route().current('teams.show')"
								>
									Team Settings
								</ResponsiveNavLink>

								<ResponsiveNavLink
									v-if="$page.props.jetstream.canCreateTeams"
									:href="route('teams.create')"
									:active="route().current('teams.create')"
								>
									Create New Team
								</ResponsiveNavLink>

								<!-- Team Switcher -->
								<template v-if="$page.props.auth.user.all_teams.length > 1">
									<div class="border-t border-gray-200 dark:border-gray-600" />

									<div class="block px-4 py-2 text-xs text-gray-400">Switch Teams</div>

									<template v-for="team in $page.props.auth.user.all_teams" :key="team.id">
										<form @submit.prevent="switchToTeam(team)">
											<ResponsiveNavLink as="button">
												<div class="flex items-center">
													<svg
														v-if="team.id == $page.props.auth.user.current_team_id"
														class="me-2 h-5 w-5 text-green-400"
														xmlns="http://www.w3.org/2000/svg"
														fill="none"
														viewBox="0 0 24 24"
														stroke-width="1.5"
														stroke="currentColor"
													>
														<path
															stroke-linecap="round"
															stroke-linejoin="round"
															d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
														/>
													</svg>
													<div>{{ team.name }}</div>
												</div>
											</ResponsiveNavLink>
										</form>
									</template>
								</template>
							</template>
						</div>
					</div>
				</div>
			</nav>

			<!-- Page Heading -->
			<header v-if="$slots.header" class="bg-white shadow dark:bg-gray-800">
				<div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
					<slot name="header" />
				</div>
			</header>

			<!-- Page Content -->
			<main class="text-black-dark dark:text-white-light">
				<slot />
			</main>
		</div>
	</div>
</template>
