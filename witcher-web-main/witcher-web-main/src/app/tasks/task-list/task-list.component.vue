<template>
	<div>
		<div class="flex flex-col md:flex-row justify-between items-center mb-5">
			<div class="flex items-center mr-2 mb-2 md:mb-0">
				<PageTitle :count="totalCount" title="Tasks" />
			</div>
			<div class="flex flex-wrap gap-4 flex-row">
				<Select
					v-model="selectedTask"
					class="w-auto text-w-grey-icons"
					button-border="border"
					:options="task"
				/>
				<div v-if="projectsLoading">Loading</div>
				<div>
					<Select
						v-if="projectsList.length > 0"
						v-model="selectedProject"
						class="w-auto text-w-grey-icons"
						button-border="border"
						:options="projectsList"
					/>
				</div>
				<Select
					v-model="selectedSort"
					class="w-auto text-w-grey-icons"
					button-border="border"
					:options="sortBy"
				/>
				<Select
					v-model="selectedStage"
					class="w-auto text-w-grey-icons"
					button-border="border"
					:options="stageList"
				/>
				<Select
					v-model="selectedSprint"
					class="w-auto text-w-grey-icons"
					button-border="border"
					:options="sprint"
				/>
				<ViewTypeButton model-value="list" />
			</div>
		</div>
		<section class="relative">
			<Row>
				<Column class="w-full lg:w-4/6">
					<div class="flex-1">
						<div class="overflow-auto w-full">
							<table
								class="min-w-full w-full"
								style="border-spacing: 0 10px; border-collapse: separate"
							>
								<thead>
									<TaskHeader />
								</thead>
								<tbody>
									<tr v-if="loading">
										<td colspan="7">Loading...</td>
									</tr>
									<tr v-else-if="error">
										<td colspan="7">Something went wrong...</td>
									</tr>
									<TaskRow
										v-for="node in taskList"
										v-else
										:key="node.id"
										:task="{
											...node,
											phase: '2',
											milestone: 'Build this VIP project',
											sprint: getLatestSprintName(node.sprints.edges) ?? 'None',
											storyPoints: '16',
											efficiency: '20%',
										}"
										@check="onTaskChecked()"
									/>
								</tbody>
							</table>
						</div>
						<div class="flex justify-end mt-2">
							<Select v-model="perPage" class="mr-4" :options="pageOptions" />
							<Pagination
								v-model:current-page="currentPage"
								:last-page="Math.ceil(total / perPage.name)"
							/>
						</div>
					</div>
				</Column>
				<Column class="hidden lg:block lg:w-2/6">
					<Sidebar />
				</Column>
			</Row>
		</section>
	</div>
</template>

<script lang="ts" setup>
import { useResult } from '@vue/apollo-composable';
import { useAppHeader } from '@witcher/plugins/app-header';
import { useI18n } from 'vue-i18n';
import { ref } from 'vue';
import { PageTitle } from '@witcher/app/components';
import { afterCursor } from '@witcher/utils/cursor';
import { pageOptions } from '@witcher/utils/paging';
import { getLatestSprintName } from '@witcher/utils/sprint';
import Select from '../../components/select/select.component.vue';
import TaskRow from '../../components/task-row/task-row-item.component.vue';
import TaskHeader from '../../components/task-row/task-row-header.component.vue';
import ViewTypeButton from '../../components/view-type-button/view-type-button.component.vue';
import Pagination from '../../components/pagination/pagination.component.vue';
import Row from '../../../components/grid/row.component.vue';
import Column from '../../../components/grid/column.component.vue';
import { useTaskListQuery } from './task-list.query.generated';
import { useProjectsQuery } from './projects.query.generated';
import Sidebar from './components/sidebar.component.vue';

const { t } = useI18n();
const { setTitle } = useAppHeader();

setTitle(t('witcher.module-title.tasks'));

// pagination
const perPage = ref(pageOptions[0]);
const currentPage = ref(1);

const { loading, result, error } = useTaskListQuery(() => ({
	perPage: perPage.value.name,
	after: afterCursor(currentPage.value - 1, perPage.value.name),
}));

const total = useResult(result, 0, (data) => data.tasks.totalCount);

const { loading: projectsLoading, result: projectsResults } =
	useProjectsQuery();

// Projects list Manipulation
const projectsList = useResult(projectsResults, [], (data) =>
	data.witcherProjects.edges.map((e) => e.node),
);
const selectedProject = ref(
	projectsList.value.length ? projectsList.value[0] : { name: 'All Projects' },
);

// Task manipulation..
const taskList = useResult(result, [], (data) =>
	data.tasks.edges.map((e) => ({ ...e.node, checked: false })),
);

const totalCount = useResult(result, 0, (data) => data.tasks.totalCount);

const task = [
	{ name: 'My Tasks Only' },
	{ name: 'Change button on home page' },
];
const selectedTask = { name: 'My Tasks Only' };
const sortBy = [{ name: 'Ascending' }, { name: 'Descending' }];
const selectedSort = { name: 'Ascending' };
const stageList = [
	{ name: 'Stage 1' },
	{ name: 'Stage 2' },
	{ name: 'Stage 3' },
	{ name: 'Stage 4' },
];
const selectedStage = { name: 'Stage 1' };
const sprint = [{ name: 'Sprint 1' }, { name: 'Sprint 2' }];
const selectedSprint = { name: 'Sprint 1' };

function onTaskChecked() {
	// this will used in phase 2
}
</script>
