<template>
	<div v-if="loading">{{ t('common.loading') }}</div>
	<div v-else-if="error">{{ t('common.error') }}</div>
	<div v-else-if="tasks.length > 0">
		<div class="flex flex-wrap gap-3 mt-8 mb-10 justify-end">
			<Select :model-value="{ name: 'Milestones' }" :options="[]" />
			<Select :model-value="{ name: 'Phase' }" :options="[]" />
			<Select :model-value="{ name: 'Sprint' }" :options="[]" />
			<Select :model-value="{ name: 'Milestones' }" :options="[]" />
			<Select :model-value="{ name: 'Order by priority' }" :options="[]" />
			<Select :model-value="{ name: 'All team members' }" :options="[]" />
			<Select :model-value="{ name: 'All tasks type' }" :options="[]" />
		</div>
		<div class="overflow-x-auto -mx-16 px-16 mb-12">
			<table class="w-full border-separate [border-spacing:0_10px]">
				<thead>
					<tr class="text-left text-sm">
						<th class="pr-2.5 pb-6" colspan="2">
							<SortButton>{{ t('common.taskRefName') }}</SortButton>
						</th>
						<th class="px-2.5 pb-6">
							<SortButton>{{ t('common.phase') }}</SortButton>
						</th>
						<th class="px-2.5 pb-6">
							<SortButton>{{ t('common.milestone') }}</SortButton>
						</th>
						<th class="px-2.5 pb-6">
							<SortButton>{{ t('common.sprint') }}</SortButton>
						</th>
						<th class="px-2.5 pb-6">
							<SortButton>{{ t('common.dueDate') }}</SortButton>
						</th>
						<th class="px-2.5 pb-6">
							<SortButton>{{ t('common.assignee') }}</SortButton>
						</th>
						<th class="px-2.5 pb-6">
							<SortButton>{{ t('common.reporter') }}</SortButton>
						</th>
						<th class="px-2.5 pb-6">
							<SortButton>{{ t('common.storyPointsShort') }}</SortButton>
						</th>
						<th class="px-2.5 pb-6">
							<SortButton>{{ t('common.activity') }}</SortButton>
						</th>
					</tr>
				</thead>
				<tbody>
					<TableRow v-for="task in tasks" :key="task.id" class="text-sm">
						<TableData class="py-2.5 pl-2" :is-first="true">
							<div class="flex items-center gap-2">
								<div class="w-1 h-9 bg-w-orange rounded-full inline-block" />
								<input type="radio" />
							</div>
						</TableData>
						<TableData class="p-2.5">
							<RouterLink
								:to="{ name: 'task-details', params: { id: task.id } }"
								class="flex items-center gap-1 after:absolute after:inset-0"
							>
								<strong class="font-semibold">{{ task.slug }}</strong>
								<span
									class="inline-block w-1 h-1 bg-w-dark-blue rounded-full"
								/>
								<span class="truncate max-w-xs">{{ task.summary }}</span>
							</RouterLink>
						</TableData>
						<TableData class="p-2.5">2º Phase</TableData>
						<TableData class="p-2.5">Milestone 1</TableData>
						<TableData class="p-2.5">
							{{ getLatestSprintName(task.sprints.edges) ?? 'None' }}
						</TableData>
						<TableData class="p-2.5">
							<div class="flex items-center gap-1">
								<span>Sep 21, 2021</span>
								<CalendarIcon class="w-6 h-6" />
							</div>
						</TableData>
						<TableData class="p-2.5 truncate">
							<Avatar
								src="https://picsum.photos/seed/1/100/100"
								class="mr-4"
								alt=""
							/>
							<span>Khyati Bardolia</span>
						</TableData>
						<TableData class="p-2.5 truncate">
							<Avatar
								src="https://picsum.photos/seed/2/100/100"
								class="mr-4"
								alt=""
							/>
							<span>Davide Bernardo</span>
						</TableData>
						<TableData class="p-2.5">
							<strong class="font-semibold">16 SP</strong>
						</TableData>
						<TableData class="p-2.5" :is-last="true">
							<strong class="font-semibold">10</strong>
						</TableData>
					</TableRow>
				</tbody>
			</table>
		</div>

		<div class="flex justify-end gap-5 mb-40">
			<Select v-model="perPage" class="w-20" :options="pageOptions" />
			<Pagination v-model:current-page="currentPage" :last-page="totalPages" />
		</div>
	</div>
</template>

<script lang="ts" setup>
import { useRoute, RouterLink } from 'vue-router';
import { computed, ref } from 'vue';
import { afterCursor } from '@witcher/utils/cursor';
import { pageOptions } from '@witcher/utils/paging';
import {
	TableRow,
	TableData,
	SortButton,
	Avatar,
	Pagination,
	Select,
} from '@app/components';
import { useI18n } from 'vue-i18n';
import { useResult } from '@vue/apollo-composable';
import { CalendarIcon } from '@witcher/icons';
import { getLatestSprintName } from '@witcher/utils/sprint';
import { useProjectTasksQuery } from './project-tasks.query.generated';

const { t } = useI18n();
const route = useRoute();

const currentPage = ref(1);
const perPage = ref(pageOptions[0]);

const { loading, error, result } = useProjectTasksQuery(() => ({
	id: route.params.id as string,
	first: perPage.value.name,
	after: afterCursor(currentPage.value - 1, perPage.value.name),
}));

const totalPages = computed(() =>
	Math.ceil(
		(result.value?.witcherProject.tasks.totalCount ?? 0) / perPage.value.name,
	),
);

const tasks = useResult(result, [], (data) =>
	data.witcherProject.tasks.edges.map((e) => e.node),
);
</script>
