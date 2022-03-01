<template>
	<TimesheetLogTask
		v-for="(taskEdge, indexTask) in allTasks"
		:key="taskEdge.node.id"
		:summary="taskEdge.node.summary"
		:slug="taskEdge.node.slug"
		:is-first="indexTask === 0"
		:is-last="indexTask === allTasks.length - 1"
		:total-activities="taskEdge.node.activities.totalCount"
		:user-name="`${faker.name.firstName()} ${faker.name.lastName()}`"
		:user-avatar="getPicsumImage(1 + indexTask)"
	>
		<TimesheetActivityList
			v-if="taskEdge.node.activities.totalCount > 0"
			:task-slug="taskEdge.node.slug"
			:activity-types="activityTypes"
		/>
	</TimesheetLogTask>

	<div v-if="loading">
		<TimesheetLogTaskSkeleton
			v-for="i in perPage.name"
			:key="i"
			:is-first="i === 1"
			:is-last="i === perPage.name"
		/>
	</div>
	<div v-else-if="error">{{ t('common.error') }}</div>
	<TimesheetLogProjectFooter
		:disabled="allTasks.length >= totalTasksCount"
		:page-options="pageOptions"
		:initial-per-page="perPage"
		@load-more="loadMore"
	/>
</template>

<script lang="ts" setup>
import { PropType, ref, watch } from 'vue';
import { useI18n } from 'vue-i18n';
import {
	TimesheetLogTask,
	TimesheetLogTaskSkeleton,
	TimesheetLogProjectFooter,
} from '@witcher/app/components';
import { afterCursor, shiftCursor } from '@witcher/utils/cursor';
import { pageOptions } from '@witcher/utils/paging';
import { faker } from '@faker-js/faker';
import { getPicsumImage } from '@witcher/utils/get-picsum-image';
import { useResult } from '@vue/apollo-composable';
import { useTimesheetProjectTasksQuery } from '../../graphql/timesheet-project-tasks.query.generated';
import TimesheetActivityList from './timesheet-activity-list.component.vue';

const props = defineProps({
	projectSlug: {
		type: String as PropType<string>,
		required: true,
	},
	activityTypes: {
		type: Array as PropType<ReadonlyArray<Record<string, unknown>>>,
		required: true,
	},
});

const { t } = useI18n();
const allTasks = ref<any[]>([]);
const perPage = ref(pageOptions[0]);
const after = ref(afterCursor(0, perPage.value.name));

const { loading, error, result } = useTimesheetProjectTasksQuery(() => ({
	slug: props.projectSlug,
	perPage: perPage.value.name,
	after: after.value,
}));

const totalTasksCount = useResult(result, 0, (data) => data.tasks.totalCount);

function addTasks(newTasks: readonly any[]) {
	allTasks.value = [...allTasks.value, ...newTasks];
}

watch(result, (last) => addTasks(last?.tasks.edges ?? []), { immediate: true });

function loadMore(totalPerPage: { name: number }) {
	// Calculate `after` using previous perPage
	after.value = shiftCursor(after.value, perPage.value.name);
	// Change perPage
	perPage.value = totalPerPage;
}
</script>
