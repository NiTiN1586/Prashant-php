<template>
	<div class="flex justify-end flex-wrap gap-3 my-4">
		<Select v-model="priority" class="w-36" :options="data" />
		<Select v-model="status" class="w-36" :options="data" />
		<Select v-model="projectsFilter" class="w-36" :options="data" />
		<Select v-model="clients" class="w-36" :options="data" />
	</div>
	<div class="overflow-x-auto">
		<table class="w-full my-8">
			<tr class="text-left text-sm whitespace-nowrap">
				<th class="pl-4 pr-8" />
				<th v-for="head in headList" :key="head" class="pl-4 pr-8">
					<SortButton>{{ head }}</SortButton>
				</th>
				<th class="pl-4 pr-8" />
			</tr>
		</table>
	</div>
	<div v-if="loading">
		<TimesheetLogProjectSkeleton
			v-for="i in perPage.name"
			:key="i"
			class="mb-6"
		/>
	</div>
	<div v-else-if="error">{{ t('common.somethingWrong') }}</div>
	<div v-else-if="result">
		<TimesheetLogProject
			v-for="(project, indexProject) in projects"
			:key="project.id"
			class="mb-6"
			:name="project.name"
			:total-tasks="project.tasks.totalCount"
			:image="getPicsumImage(1 + indexProject)"
		>
			<TimesheetTaskList
				:project-slug="project.slug"
				:activity-types="activityTypes"
			/>
		</TimesheetLogProject>
	</div>
	<div class="flex justify-end gap-3 mb-32">
		<Select v-model="perPage" :options="pageOptions" />
		<Pagination
			v-model:current-page="currentPage"
			:last-page="Math.ceil(total / perPage.name)"
		/>
	</div>
</template>

<script lang="ts" setup>
import { Select, Pagination, SortButton } from '@app/components';
import { afterCursor } from '@witcher/utils/cursor';
import { ref } from 'vue';
import { useI18n } from 'vue-i18n';
import { useResult } from '@vue/apollo-composable';
import { pageOptions } from '@witcher/utils/paging';
import {
	TimesheetLogProject,
	TimesheetLogProjectSkeleton,
} from '@witcher/app/components';
import { getPicsumImage } from '@witcher/utils/get-picsum-image';
import { useTimesheetProjectsQuery } from '../../graphql/timesheet-projects.query.generated';
import TimesheetTaskList from './timesheet-task-list.component.vue';

const { t } = useI18n();

const perPage = ref(pageOptions[0]);
const currentPage = ref(1);

const { result, loading, error } = useTimesheetProjectsQuery(() => ({
	perPage: perPage.value.name,
	after: afterCursor(currentPage.value - 1, perPage.value.name),
}));
const total = useResult(result, 0, (data) => data.witcherProjects.totalCount);
const projects = useResult(result, [], (data) =>
	data.witcherProjects.edges.map((e) => e.node),
);

const activityTypes = useResult(result, [] as [], (data) =>
	data.activityTypes.edges.map((e) => e.node),
);

const priority = ref({ name: 'Priority' });
const status = ref({ name: 'Status' });
const projectsFilter = ref({ name: 'All projects' });
const clients = ref({ name: 'All client' });
const data = [{ name: 'Wade Cooper' }, { name: 'Arlene Mccoy' }];

const headList = [
	'Activity/project name',
	'Tech',
	'Source',
	'Phase',
	'Milestone',
	'Sprint',
	'Creation Date',
	'SP',
	'Hours',
];
</script>
