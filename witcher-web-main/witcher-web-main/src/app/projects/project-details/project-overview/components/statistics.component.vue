<template>
	<div class="rounded bg-w-grey-background-light p-6">
		<Disclosure v-slot="{ open }" :default-open="show">
			<div class="flex justify-between items-center">
				<h3>
					{{ t('common.statistics') }}
				</h3>
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
					class="grid gap-6 grid-cols-1 lg:grid-cols-2 2xl:grid-cols-4 mt-5"
				>
					<div class="grid gap-6">
						<Card>
							<SmallWidget title="260" subtitle="Total SP points consumed">
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
										fill-rule="evenodd"
										clip-rule="evenodd"
										d="M20.1898 33.3327L10.666 24.3755L13.3517 21.8496L20.1898 28.2629L34.647 14.666L37.3327 17.2098L20.1898 33.3327Z"
										fill="#072EF5"
									/>
								</svg>
							</SmallWidget>
						</Card>
						<Card>
							<SmallWidget title="550:23" subtitle="Total Hours Consumed">
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
										fill-opacity="0.1"
									/>
									<path
										d="M23.3483 13.695C23.5234 13.5443 23.7513 13.4531 24.0005 13.4531C29.9906 13.4531 34.8466 18.3091 34.8466 24.2993C34.8466 30.2895 29.9906 35.1455 24.0005 35.1455C18.0103 35.1455 13.1543 30.2895 13.1543 24.2993C13.1543 23.747 13.602 23.2993 14.1543 23.2993C14.7066 23.2993 15.1543 23.747 15.1543 24.2993C15.1543 29.1849 19.1149 33.1455 24.0005 33.1455C28.8861 33.1455 32.8466 29.1849 32.8466 24.2993C32.8466 19.6097 29.1975 15.7724 24.5838 15.4721L24.5838 24.5557C24.5838 25.108 24.1361 25.5557 23.5838 25.5557C23.0315 25.5557 22.5838 25.108 22.5838 24.5557L22.5838 14.6671C22.5838 14.1959 22.9097 13.8009 23.3483 13.695Z"
										fill="#333984"
									/>
								</svg>
							</SmallWidget>
						</Card>
					</div>
					<TimeCard />
					<PhaseOverview />
					<MilestoneOverview />
				</DisclosurePanel>
			</transition>
		</Disclosure>
	</div>
</template>

<script lang="ts">
import { defineComponent } from '@vue/runtime-core';
import { useLocalStorage } from '@vueuse/core';
import { Disclosure, DisclosureButton, DisclosurePanel } from '@headlessui/vue';
import { useI18n } from 'vue-i18n';
import { Card } from '@witcher/app/components';
import { HideEyeIcon } from '@witcher/icons';
import SmallWidget from '../../../../components/small-widget/small-widget.component.vue';
import TimeCard from '../../../../components/time-card/time-card.component.vue';
import MilestoneOverview from './milestone-overview.component.vue';
import PhaseOverview from './phase-overview.component.vue';

export default defineComponent({
	components: {
		MilestoneOverview,
		PhaseOverview,
		SmallWidget,
		TimeCard,
		Disclosure,
		DisclosureButton,
		DisclosurePanel,
		Card,
		HideEyeIcon,
	},
	setup() {
		const { t } = useI18n();
		const key = 'projects.project-details.overview.show-statistics';
		const show = useLocalStorage(key, true);

		return { show, t };
	},
});
</script>
