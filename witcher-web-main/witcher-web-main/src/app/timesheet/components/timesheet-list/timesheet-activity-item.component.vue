<template>
	<TimesheetLogActivity
		:key="activity.id"
		:activity-types="activityTypes"
		:activity-type="{
			friendlyName: activity.activityType.friendlyName,
		}"
		:technologies="languages"
		:technology="{ friendlyName: 'PHP' }"
		:repositories="sources"
		:repository="{ friendlyName: 'Git repo' }"
		:phase="`${faker.datatype.number(5)}º Phase`"
		:milestone="faker.commerce.productName()"
		:sprint="`Sprint ${faker.datatype.number(10)}`"
		:created-date="activity.activityAt"
		:estimation-time="activity.estimationTime"
		:estimation-sp="activity.estimationSp"
		@update:activity-type="updateActivityType"
		@update:technology="noop"
		@update:repository="noop"
		@update:estimation-time="updateEstimationTime"
		@update:estimation-sp="updateEstimationSp"
		@delete="activityDeleteMutation.mutate()"
		@update:activity-at="updateActivityAt"
	></TimesheetLogActivity>
</template>

<script lang="ts" setup>
import { faker } from '@faker-js/faker';
import { debouncedWatch } from '@vueuse/core';
import { TimesheetLogActivity } from '@witcher/app/components';
import {
	useActivityUpdateMutation,
	useActivityDeleteMutation,
} from '@witcher/app/graphql';
import { getTemporaryId } from '@witcher/utils/apollo-utils';
import { formatDuration, timeToDuration } from '@witcher/utils/time';
import { PropType, ref } from 'vue';

const props = defineProps({
	activity: {
		type: Object as PropType<Object>,
		required: true,
	},
	activityTypes: {
		type: Array as PropType<ReadonlyArray<Record<string, unknown>>>,
		required: true,
	},
});

function noop() {}

const activityDeleteMutation = useActivityDeleteMutation({
	variables: { id: props.activity.id },
	optimisticResponse: {
		__typename: 'Mutation',
		deleteActivity: {
			__typename: 'deleteActivityPayload',
			activity: {
				__typename: 'Activity',
				id: props.activity.id,
			},
		},
	},
	update(cache) {
		cache.modify({
			fields: {
				activities: (connection: any, { readField }: any) => ({
					...connection,
					edges: connection.edges.filter((edge: any) => {
						const id = readField('id', edge.node);
						return props.activity.id !== id;
					}),
				}),
			},
		});
	},
});

const selectedActivityType = ref(props.activity.activityType);
const time = ref(formatDuration(props.activity.estimationTime));
const sp = ref(props.activity.estimationSp);
const activityAt = ref(props.activity.activityAt);

const activityUpdateMutation = useActivityUpdateMutation({});

function updateActivityType(activityType: Record<string, unknown>) {
	selectedActivityType.value = activityType;
	updateActivity();
}

function updateActivityAt(date: string) {
	activityAt.value = date;
	updateActivity();
}

function updateEstimationTime(estimationTime: string) {
	time.value = estimationTime;
}

function updateEstimationSp(estimationSp: string) {
	sp.value = estimationSp;
}

debouncedWatch(time, () => updateActivity(), { debounce: 1000 });
debouncedWatch(sp, () => updateActivity(), { debounce: 1000 });

const updateActivity = () => {
	activityUpdateMutation.mutate({
		input: {
			id: props.activity.id,
			clientMutationId: getTemporaryId(),
			estimationTime: timeToDuration(time.value),
			estimationSp: Number(sp.value),
			comment: props.activity.comment,
			activityType: selectedActivityType.value.id,
			activityAt: activityAt.value,
		},
	});
};

const languages = [
	{ friendlyName: 'Vue' },
	{ friendlyName: 'React' },
	{ friendlyName: 'Angular' },
	{ friendlyName: '.Net' },
	{ friendlyName: 'PHP' },
];
const sources = [
	{ friendlyName: 'Git Repo' },
	{ friendlyName: 'Gitlab Repo' },
	{ friendlyName: 'SVN' },
];
</script>
