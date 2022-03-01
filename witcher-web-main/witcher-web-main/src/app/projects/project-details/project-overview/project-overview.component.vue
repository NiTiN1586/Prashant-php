<template>
	<Statistics class="mb-8" />
	<Row>
		<Column class="flex-1">
			<div class="mb-6 p-6 rounded border border-w-light-grey h-[300px]">
				<h3 class="font-medium text-lg">
					{{ t('projects.project-details.weekly-overflow') }}
				</h3>
				<div class="text-w-light-grey flex items-center justify-center h-full">
					<svg
						class="w-20 h-20"
						fill="none"
						stroke="currentColor"
						viewBox="0 0 24 24"
						xmlns="http://www.w3.org/2000/svg"
					>
						<path
							stroke-linecap="round"
							stroke-linejoin="round"
							stroke-width="2"
							d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"
						></path>
					</svg>
				</div>
			</div>

			<div class="mb-6 p-6 rounded border border-w-light-grey h-[300px]">
				<h3 class="font-medium text-lg">
					{{ t('projects.project-details.team-ranking') }}
				</h3>
				<div class="text-w-light-grey flex items-center justify-center h-full">
					<svg
						class="w-20 h-20"
						fill="none"
						stroke="currentColor"
						viewBox="0 0 24 24"
						xmlns="http://www.w3.org/2000/svg"
					>
						<path
							stroke-linecap="round"
							stroke-linejoin="round"
							stroke-width="2"
							d="M8 13v-1m4 1v-3m4 3V8M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"
						></path>
					</svg>
				</div>
			</div>

			<h3 class="font-medium text-lg">
				{{ t('common.recent-activity') }}
			</h3>

			<div class="flex gap-3 text-sm my-6">
				<button class="px-3 py-2 rounded-lg bg-w-grey-background-light">
					{{ t('common.all') }}
				</button>
				<button class="px-3 py-2 rounded-lg text-w-grey-icons">
					{{ t('common.activities') }}
				</button>
				<button class="px-3 py-2 rounded-lg text-w-grey-icons">
					{{ t('common.comments') }}
				</button>
			</div>

			<div
				v-for="i in 3"
				:key="i"
				class="py-6 border-b border-w-light-grey text-sm"
			>
				<div class="flex gap-3 items-center mb-4">
					<div class="inline-flex items-center gap-3">
						<Avatar alt="" src="https://picsum.photos/100/100" />
						<span class="font-semibold">Khyati Bardolia</span>
						<span class="text-w-grey-icons">added a</span>
						<span
							class="bg-w-grey-background-light text-w-blue-deep px-3 py-1 rounded-lg"
						>
							Comment
						</span>
						<span class="text-w-grey-icons">on</span>
						<span
							class="bg-w-grey-background-light text-w-blue-deep px-3 py-1 rounded-lg"
						>
							#Jag001
						</span>
					</div>
					<span class="ml-auto">5 hours ago</span>
				</div>
				<div class="flex gap-3">
					<div class="w-9" />
					<p>The client loved the project and he wants to continue :)</p>
				</div>
			</div>
		</Column>
		<Column class="hidden xl:block w-[480px]">
			<div class="rounded border border-w-light-grey">
				<div class="p-6">
					<h3 class="font-medium text-lg mb-10">
						{{ t('projects.project-details.efficiency-rate') }}
					</h3>

					<div class="mb-12">
						<div class="inline-grid items-center justify-items-center">
							<SvgCircle
								:diameter="210"
								:current="65"
								:stroke-width="14"
								class="col-span-full row-span-full"
								foreground-color="text-w-blue-deep"
							/>
							<SvgCircle
								:diameter="153"
								:current="15"
								:stroke-width="14"
								class="col-span-full row-span-full"
								foreground-color="text-w-vivid-green"
							/>
							<div class="col-span-full row-span-full">
								<div class="font-semibold text-2xl">150h</div>
								<div class="text-sm">Worked</div>
							</div>
						</div>
					</div>

					<div class="grid gap-6">
						<div v-for="i in 5" :key="i" class="flex items-center gap-3">
							<Avatar src="https://picsum.photos/100/100" alt="" />
							<div class="flex-1">
								<div class="flex items-center gap-3 mb-2">
									<span>Khyati Bardolia</span>
									<span
										class="px-3 py-1 bg-w-grey-background-light rounded-lg text-w-blue-deep text-sm"
									>
										Frontend Dev
									</span>
									<span class="ml-auto font-medium text-lg">
										{{ 20 + i * 10 }}%
									</span>
								</div>
								<Progress :progress="20 + i * 10" />
							</div>
						</div>
					</div>
				</div>
				<hr class="text-w-light-grey" />
				<div class="p-6">
					<h3 class="font-medium text-lg mb-6">
						{{ t('projects.project-details.consuming-activities') }}
					</h3>

					<div class="grid gap-6">
						<div v-for="i in 3" :key="i">
							<div class="flex items-center gap-3 mb-2">
								<span>Development</span>
								<span class="ml-auto">32:05 Hrs</span>
							</div>
							<Progress :progress="20 + i * 10" />
						</div>
					</div>
				</div>
			</div>
		</Column>
	</Row>
</template>

<script lang="ts">
import { defineComponent } from '@vue/runtime-core';
import { Avatar, Progress, SvgCircle } from '@app/components';
import { Column, Row } from '@witcher/components';
import { useI18n } from 'vue-i18n';
import Statistics from './components/statistics.component.vue';

export default defineComponent({
	components: {
		Avatar,
		Progress,
		SvgCircle,
		Statistics,
		Row,
		Column,
	},
	setup() {
		const { t } = useI18n();
		return { t };
	},
});
</script>
