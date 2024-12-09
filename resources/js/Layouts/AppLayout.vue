<script setup>
import { onMounted, ref } from 'vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Toaster from '@/Components/shadcn/sonner/Sonner.vue';
import { toast } from 'vue-sonner';

defineProps({
	title: String,
});

const showingProfileDropdown = ref(false);
const showingNavigationDropdown = ref(false);

const logout = () => {
	router.post(route('logout'));
};

const page = usePage();
const message = ref('');

onMounted(() => {
	const flash = page.props.flash;
	const messageSet = new Set(Object.values(flash));
	const toastProps = {
		action: {
			label: 'Got it',
		},
	};
	if (messageSet.size !== 1) {
		message.value = flash.error || flash.success || '';
		const toastFunc = flash.error ? toast.error : flash.success ? toast.success : toast.info;
		return toastFunc(message.value, toastProps);
	}
});
</script>

<template>
	<div>
		<Head :title="title" />
		<Toaster />
		<div class="min-h-screen bg-white-dark text-black-dark dark:bg-black-dark dark:text-white-light">
			<nav class="bg-white text-black-dark dark:bg-black dark:text-white-light">
				<!-- Primary Navigation Menu -->
				<div class="mx-auto flex h-16 max-w-7xl justify-between px-4 sm:px-6 lg:px-8">
					<!-- Logo -->
					<div class="flex shrink-0 items-center">
						<Link
							:href="$page.props.auth.user ? route('dashboard') : route('welcome')"
							class="group relative flex h-full flex-row items-center gap-3"
						>
							<p
								class="flex h-full w-auto items-center font-serif text-3xl font-bold text-primary dark:text-primary-extralight"
							>
								Eventacle
							</p>
							<ApplicationLogo
								class="absolute right-0 top-2 max-w-12 opacity-40 group-hover:animate-shake"
							/>
						</Link>
					</div>

					<template v-if="$page.props.auth.user">
						<div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex sm:flex-1 sm:justify-end">
							<NavLink :href="route('dashboard')" :active="route().current('dashboard')">
								Dashboard
							</NavLink>
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
										<div class="block px-4 py-2 text-xs text-black-dark dark:text-white-light">
											Manage Account
										</div>

										<DropdownLink :href="route('profile.show')"> Profile </DropdownLink>

										<div class="border-t border-primary-light dark:border-primary-dark" />

										<!-- Authentication -->
										<form @submit.prevent="logout">
											<DropdownLink as="button"> Log Out </DropdownLink>
										</form>
									</template>
								</Dropdown>
							</div>
						</div>
					</template>
					<template v-else>
						<div class="-my-px ms-10 flex flex-1 items-center justify-end space-x-8">
							<Link :href="route('login')">
								<PrimaryButton type="button" aria-label="Access login or register page">
									<i-ic-round-login class="-translate-x-[2px] text-xl" aria-label="Login/Register" />
								</PrimaryButton>
							</Link>
						</div>
					</template>
					<!-- Navigation Links -->

					<!-- Hamburger -->
					<div v-if="$page.props.auth.user" class="-me-2 flex items-center sm:hidden">
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
					<!-- Responsive Settings Options -->
					<template v-if="$page.props.auth.user">
						<div class="space-y-1 pb-3 pt-2">
							<ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')">
								Dashboard
							</ResponsiveNavLink>
							<ResponsiveNavLink :href="route('event.create')" :active="route().current('event.create')">
								Create Event
							</ResponsiveNavLink>
						</div>
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
									<div class="text-base font-medium text-black-dark dark:text-white-light">
										{{ $page.props.auth.user.name }}
									</div>
									<div class="text-sm font-medium text-black-light dark:text-white-dark">
										{{ $page.props.auth.user.email }}
									</div>
								</div>
							</div>

							<div class="mt-3 space-y-1">
								<ResponsiveNavLink
									:href="route('profile.show')"
									:active="route().current('profile.show')"
								>
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
					</template>
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
				<div class="mx-auto flex max-w-7xl flex-col py-12 sm:px-6 lg:px-8">
					<slot />
				</div>
			</main>
		</div>
	</div>
</template>
