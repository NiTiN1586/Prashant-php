<template>
	<div class="flex h-screen w-screen">
		<nav
			class="bg-white shadow h-screen overflow-y-auto flex-shrink-0 z-20 fixed top-0 left-0 xl:relative"
			:class="{
				'hidden xl:block p-6 xl:py-6 xl:px-0': !isMenuOpen,
				'p-6': isMenuOpen,
			}"
		>
			<Sidebar />
		</nav>
		<div
			v-if="isMenuOpen"
			class="bg-black/50 fixed inset-0 z-10 xl:hidden"
			@click="hideMenu"
		/>
		<main class="flex flex-col flex-grow overflow-y-auto isolate">
			<Header />
			<section class="bg-white flex-grow py-5">
				<Container variant="2xl:container" padding="px-6 2xl:px-16">
					<RouterView />
				</Container>
			</section>
		</main>
	</div>
</template>

<script lang="ts" setup>
import { Container } from '@witcher/components';
import { useSettings } from '../plugins/settings';
import Header from './components/header.component.vue';
import Sidebar from './components/sidebar.component.vue';

const { hideMenu, isMenuOpen } = useSettings();
</script>
