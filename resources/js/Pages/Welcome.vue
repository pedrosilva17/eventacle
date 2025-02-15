<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import LandingPageCard from '@/Components/LandingPageCard.vue';
import LandingPageContainer from '@/Components/LandingPageContainer.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
</script>

<template>
	<AppLayout title="Welcome">
		<Transition name="slide-fade" mode="out-in" appear>
			<h1 class="mb-8 mt-12 flex w-full flex-col items-center text-5xl sm:text-8xl">
				No spending.
				<Transition name="slide-fade" mode="out-in" style="transition-delay: 0.5s" appear>
					<span class="inline-flex">No pressure.</span>
				</Transition>
				<Transition name="slide-fade" mode="out-in" style="transition-delay: 1s" appear>
					<span class="animate-scale-loop font-serif text-primary dark:text-primary-light">All the fun!</span>
				</Transition>
			</h1>
		</Transition>
		<PrimaryButton :href="route('register')" class="group mx-auto -mb-6 mt-2 w-fit">
			<span class="text-2xl group-hover:animate-pulse">Register</span>
		</PrimaryButton>
		<div class="ml-[50%] mt-8 flex w-screen -translate-x-1/2 flex-col">
			<LandingPageContainer>
				<template #heading> Zero bets - all brags </template>
				<template #content>
					<p class="flex max-w-2xl text-center text-lg">
						Whether you're into sports, award shows or the regional Magic the Gathering tournament,
						Eventacle lets you test your prediction skills - no money involved. Create events, climb
						leaderboards, and prove you're the best at forecasting the future!
					</p>
				</template>
			</LandingPageContainer>
			<LandingPageContainer>
				<template #heading> What you can do </template>
				<template #content>
					<div class="mx-auto grid w-fit grid-cols-1 justify-around gap-6 lg:grid-cols-3">
						<LandingPageCard title="Create">
							<template #icon>
								<i-ic-round-event class="text-2xl sm:text-4xl" />
							</template>
							<template #content>
								Creating your own event gives you the freedom to start predicting anything - even the
								results of your local tennis tournament.
							</template>
						</LandingPageCard>
						<LandingPageCard title="Predict">
							<template #icon>
								<i-ic-round-auto-awesome class="text-2xl sm:text-4xl" />
							</template>
							<template #content>
								Put your expert hat on and predict the outcomes of the event's contests: you might even
								have to measure how confident you are!
							</template>
						</LandingPageCard>
						<LandingPageCard title="Compete">
							<template #icon>
								<i-ic-round-leaderboard class="text-2xl sm:text-4xl" />
							</template>
							<template #content>
								After the event is over, a leaderboard is formed with everyone that participated in the
								predictions. Can you get to first place?
							</template>
						</LandingPageCard>
					</div>
				</template>
			</LandingPageContainer>
			<LandingPageContainer>
				<template #heading> What makes us unique </template>
				<template #content>
					<div class="mx-auto grid w-fit grid-cols-1 justify-around gap-6 lg:grid-cols-3">
						<LandingPageCard title="No money">
							<template #icon>
								<i-ic-round-money-off class="text-2xl sm:text-4xl" />
							</template>
							<template #content>
								Never lose a penny: all predictions are stress-free, only bragging rights are on the
								line!
							</template>
						</LandingPageCard>
						<LandingPageCard title="More than sports">
							<template #icon>
								<i-ic-round-checklist class="text-2xl sm:text-4xl" />
							</template>
							<template #content>
								Don't like football? Predict the Oscar winners. Tired of basketball? Guess which one
								will be the Game of the Year.
							</template>
						</LandingPageCard>
						<LandingPageCard title="Fun for everyone">
							<template #icon>
								<i-ic-round-group class="text-2xl sm:text-4xl" />
							</template>
							<template #content>
								No betting means anyone can participate and enjoy friendly competition!
							</template>
						</LandingPageCard>
					</div>
				</template>
			</LandingPageContainer>
			<section class="mt-12 flex w-full flex-col items-center gap-6 py-16">
				<h2 class="text-2xl font-bold sm:text-4xl">Join the fun!</h2>
				<PrimaryButton :href="route('register')" class="group">
					<span class="text-2xl group-hover:animate-pulse">Register</span>
				</PrimaryButton>
			</section>
		</div>
	</AppLayout>
</template>
