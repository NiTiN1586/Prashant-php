<template>
	<div class="rounded p-5 bg-gray-100">
		<Disclosure v-slot="{ open }" :default-open="show">
			<div class="flex justify-between items-center">
				<h3>Overall Statistics</h3>
				<DisclosureButton
					class="text-w-grey-icons flex items-center gap-2"
					@click="show = !open"
				>
					<span v-if="open">{{ t('common.hide') }}</span>
					<span v-if="!open">{{ t('common.show') }}</span>
					<HideEyeIcon class="w-6 h-6" />
				</DisclosureButton>
			</div>
			<transition
				enter-active-class="transition duration-100 ease-out"
				enter-from-class="transform scale-95 opacity-0"
				enter-to-class="transform scale-100 opacity-100"
				leave-active-class="transition duration-75 ease-out"
				leave-from-class="transform scale-100 opacity-100"
				leave-to-class="transform scale-95 opacity-0"
			>
				<DisclosurePanel
					class="grid gap-5 grid-cols-1 lg:grid-cols-2 2xl:grid-cols-4 mt-5"
				>
					<div class="grid gap-5">
						<Card>
							<SmallWidget
								title="260"
								subtitle="Total SP points consumed"
								class="bg-white"
							>
								<svg
									width="48"
									height="48"
									viewBox="0 0 48 48"
									fill="none"
									xmlns="http://www.w3.org/2000/svg"
								>
									<rect
										width="48"
										height="48"
										rx="24"
										fill="#FF005C"
										fill-opacity="0.05"
									/>
									<path
										d="M31.2 14.666L26.4 19.6438H30V28.3549C30 29.7238 28.92 30.8438 27.6 30.8438C26.28 30.8438 25.2 29.7238 25.2 28.3549V19.6438C25.2 16.8936 23.052 14.666 20.4 14.666C17.748 14.666 15.6 16.8936 15.6 19.6438V28.3549H12L16.8 33.3327L21.6 28.3549H18V19.6438C18 18.2749 19.08 17.1549 20.4 17.1549C21.72 17.1549 22.8 18.2749 22.8 19.6438V28.3549C22.8 31.1051 24.948 33.3327 27.6 33.3327C30.252 33.3327 32.4 31.1051 32.4 28.3549V19.6438H36L31.2 14.666Z"
										fill="#FF005C"
									/>
								</svg>
							</SmallWidget>
						</Card>
						<Card>
							<SmallWidget
								title="550:23"
								subtitle="Total Hours Consumed"
								class="bg-white"
							>
								<svg
									width="48"
									height="48"
									viewBox="0 0 48 48"
									fill="none"
									xmlns="http://www.w3.org/2000/svg"
								>
									<rect
										width="48"
										height="48"
										rx="24"
										fill="#072EF5"
										fill-opacity="0.05"
									/>
									<path
										d="M23.3483 13.695C23.5234 13.5443 23.7513 13.4531 24.0005 13.4531C29.9906 13.4531 34.8466 18.3091 34.8466 24.2993C34.8466 30.2895 29.9906 35.1455 24.0005 35.1455C18.0103 35.1455 13.1543 30.2895 13.1543 24.2993C13.1543 23.747 13.602 23.2993 14.1543 23.2993C14.7066 23.2993 15.1543 23.747 15.1543 24.2993C15.1543 29.1849 19.1149 33.1455 24.0005 33.1455C28.8861 33.1455 32.8466 29.1849 32.8466 24.2993C32.8466 19.6097 29.1975 15.7724 24.5838 15.4721L24.5838 24.5557C24.5838 25.108 24.1361 25.5557 23.5838 25.5557C23.0315 25.5557 22.5838 25.108 22.5838 24.5557L22.5838 14.6671C22.5838 14.1959 22.9097 13.8009 23.3483 13.695Z"
										fill="#333984"
									/>
								</svg>
							</SmallWidget>
						</Card>
					</div>
					<TimeCard class="bg-white" />
					<Card>
						<ProgressWidget
							class="bg-white"
							title="Most invested projects"
							:items="items"
						/>
					</Card>
					<RadialCard class="bg-white" :data="data" />
				</DisclosurePanel>
			</transition>
		</Disclosure>
	</div>
</template>

<script lang="ts">
import { defineComponent } from '@vue/runtime-core';
import { useLocalStorage } from '@vueuse/core';
import { useI18n } from 'vue-i18n';
import { Disclosure, DisclosureButton, DisclosurePanel } from '@headlessui/vue';
import SmallWidget from '@app/components/small-widget/small-widget.component.vue';
import TimeCard from '@app/components/time-card/time-card.component.vue';
import RadialCard from '@app/components/radial-card/radial-card.component.vue';
import { Card, ProgressWidget } from '@app/components';
import { HideEyeIcon } from '@witcher/icons';

export default defineComponent({
	components: {
		SmallWidget,
		TimeCard,
		ProgressWidget,
		Card,
		RadialCard,
		Disclosure,
		DisclosureButton,
		DisclosurePanel,
		HideEyeIcon,
	},
	setup() {
		const { t } = useI18n();
		const key = 'projects.project-list.show-statistics';
		const show = useLocalStorage(key, true);
		const items = [
			{ title: 'Development', value: '13:00 Hrs', progress: 10 },
			{ title: 'Meeting', value: '15:22 Hrs', progress: 50 },
			{ title: 'UI/UX Design', value: '23:05 Hrs', progress: 30 },
		];

		const data = {
			totalTask: 375,
			completedTask: 20,
		};
		return { items, data, show, t };
	},
});
</script>
