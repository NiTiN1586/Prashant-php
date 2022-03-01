<template>
	<section class="relative">
		<Row class="mt-12">
			<Column class="w-full">
				<PageTitle
					:title="t('dashboard.tasks.openTasks')"
					:count="result ? result.tasks.edges.length : 0"
				/>
				<RouterLink
					class="float-right -mt-6 font-normal text-sm text-w-grey-icons"
					to="/tasks"
				>
					{{ t('dashboard.tasks.viewAll.link') }}
				</RouterLink>
			</Column>
			<Column class="w-full">
				<div v-if="loading">{{ t('common.loading') }}</div>
				<div v-else-if="error">{{ t('common.error') }}</div>
				<div v-else-if="result?.tasks.edges" class="overflow-x-auto mb-12">
					<table class="w-full border-separate [border-spacing:0_10px]">
						<thead>
							<tr class="text-left text-sm">
								<th class="pr-2.5 pb-6" colspan="2">
									<SortButton>{{ t('common.taskRefName') }}</SortButton>
								</th>
								<th class="px-2.5 pb-6">
									<SortButton>{{ t('common.project') }}</SortButton>
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
									<SortButton>{{ t('common.storyPointsShort') }}</SortButton>
								</th>
								<th class="px-2.5 pb-6">
									<SortButton>{{ t('common.efficiency') }}</SortButton>
								</th>
							</tr>
						</thead>
						<tbody>
							<TableRow
								v-for="task in result?.tasks.edges"
								:key="task.node.id"
								class="text-sm"
							>
								<TableData class="py-2.5 pl-2" is-first>
									<div class="flex items-center gap-2">
										<div class="w-1 h-9 bg-w-pink rounded-full inline-block" />
										<input type="radio" />
									</div>
								</TableData>
								<TableData class="p-2.5 relative">
									<RouterLink
										:to="{ name: 'task-details', params: { id: task.node.id } }"
										class="flex items-center gap-1 after:absolute after:inset-0"
									>
										<strong class="font-semibold">{{ task.node.slug }}</strong>
										<span
											class="inline-block w-1 h-1 bg-w-dark-blue rounded-full"
										/>
										<span class="truncate max-w-xs">
											{{ task.node.summary }}
										</span>
									</RouterLink>
								</TableData>
								<TableData class="p-2.5 relative">
									<RouterLink
										:to="{
											name: 'project-details',
											params: { id: task.node.witcherProject.id },
										}"
										class="flex items-center gap-2 after:absolute after:inset-0"
									>
										{{ task.node.witcherProject.slug }}
										<ExternalLinkIcon class="h-[16px] w-[16px]" />
									</RouterLink>
								</TableData>
								<TableData class="p-2.5 text-gray-300">---</TableData>
								<TableData class="p-2.5 text-gray-300">---</TableData>
								<TableData class="p-2.5">
									{{ getLatestSprintName(task.node.sprints.edges) ?? 'None' }}
								</TableData>
								<TableData class="p-2.5 text-gray-300">---</TableData>
								<TableData class="p-2.5 text-gray-300" is-last>---</TableData>
							</TableRow>
						</tbody>
					</table>
				</div>
			</Column>
		</Row>
	</section>
</template>

<script lang="ts" setup>
import { useRoute, RouterLink } from 'vue-router';

import { afterCursor } from '@witcher/utils/cursor';
import { TableRow, TableData, SortButton } from '@app/components';
import { useI18n } from 'vue-i18n';
import ExternalLinkIcon from '@witcher/icons/external-link.component.vue';
import { getLatestSprintName } from '@witcher/utils/sprint';
import Row from '../../../components/grid/row.component.vue';
import Column from '../../../components/grid/column.component.vue';
import PageTitle from '../../components/page-title/page-title.component.vue';
import { useWeeklyTasksQuery } from './weekly-task.query.generated';

const { t } = useI18n();
const route = useRoute();

const { loading, error, result } = useWeeklyTasksQuery(() => ({
	id: route.params.id as string,
	perPage: 10,
	after: afterCursor(0, 0),
}));
</script>
