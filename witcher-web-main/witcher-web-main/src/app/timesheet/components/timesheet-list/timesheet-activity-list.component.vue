<template>
	<TimesheetActivityItem
		v-for="activity in activities"
		:key="activity.id"
		:activity-types="activityTypes"
		:activity="activity"
	></TimesheetActivityItem>
	<template v-if="loading">
		<TimesheetLogActivitySkeleton v-for="i in 10" :key="i" />
	</template>
	<div v-else-if="error">{{ t('common.error') }}</div>
</template>

<script lang="ts" setup>
import { useI18n } from 'vue-i18n';
import { useResult } from '@vue/apollo-composable';
import { PropType } from 'vue';
import { useTimesheetTaskActivitiesQuery } from '../../graphql/timesheet-activities.query.generated';
import TimesheetLogActivitySkeleton from '../../../components/timesheet-log/timesheet-log-activity-skeleton.component.vue';
import TimesheetActivityItem from './timesheet-activity-item.component.vue';

const props = defineProps({
	taskSlug: {
		type: String as PropType<string>,
		required: true,
	},
	activityTypes: {
		type: Array as PropType<ReadonlyArray<Record<string, unknown>>>,
		required: true,
	},
});

const { t } = useI18n();

const { loading, error, result } = useTimesheetTaskActivitiesQuery(() => ({
	perPage: 1000,
	slug: props.taskSlug,
}));

const activities = useResult(result, [], (data) =>
	data.activities.edges.map((e) => e.node),
);
</script>
