<template>
	<TableRow :key="activity.id">
		<TableData class="px-4 py-0" is-first>
			<Select
				v-model="activityType"
				:options="activityTypes"
				button-border=""
				id-field="id"
				name-field="friendlyName"
			/>
		</TableData>
		<TableData class="p-4">PHP</TableData>
		<TableData class="p-4">Git repo</TableData>
		<TableData class="p-4">
			<div class="flex items-center gap-1">
				<DatepickerInput
					v-model="activityAt"
					input-class="w-[7rem] border-none"
					:input-attr="{ readOnly: true }"
					is-required
				/>
			</div>
		</TableData>
		<TableData class="px-4">
			<Input
				v-model="time"
				type="time"
				:input-class="[
					'w-[70px]',
					timeToDuration(time) > 0 ? 'border-w-vivid-green' : 'border-w-pink',
				]"
			/>
		</TableData>
		<TableData class="px-4">
			<Input v-model="sp" type="number" input-class="w-[70px]" />
		</TableData>
		<TableData is-last>
			<button class="p-2" @click="activityDeleteMutation.mutate()">
				<CloseIcon class="w-6 h-6 text-w-pink" />
			</button>
			<div
				v-if="activityDeleteMutation.loading.value || isTemporaryNode(activity)"
				class="z-10 absolute inset-0 bg-gray-300 rounded opacity-10"
			/>
		</TableData>
	</TableRow>
</template>

<script lang="ts" setup>
import { PropType, ref } from 'vue';
import {
	TableRow,
	TableData,
	Select,
	Input,
	DatepickerInput,
} from '@witcher/app/components';
import { CloseIcon } from '@witcher/icons';
import { getTemporaryId, isTemporaryNode } from '@witcher/utils/apollo-utils';
import { debouncedWatch } from '@vueuse/core';
import { formatDuration, timeToDuration } from '@witcher/utils/time';
import {
	useActivityUpdateMutation,
	useActivityDeleteMutation,
} from '@witcher/app/graphql';

const emit = defineEmits(['deleted']);
const props = defineProps({
	activity: {
		type: Object as PropType<{
			id: string;
			[key: string]: any;
		}>,
		required: true,
	},
	activityTypes: {
		type: Array as PropType<ReadonlyArray<Record<string, unknown>>>,
		required: true,
	},
});

const activityUpdateMutation = useActivityUpdateMutation({});
const activityDeleteMutation = useActivityDeleteMutation({
	update: (cache, result) => emit('deleted', cache, result),
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
});

const activityType = ref(props.activity.activityType);
const time = ref(formatDuration(props.activity.estimationTime));
const sp = ref(props.activity.estimationSp.toString());
const activityAt = ref(new Date(props.activity.activityAt ?? new Date()));

function updateActivity() {
	activityUpdateMutation.mutate({
		input: {
			id: props.activity.id,
			clientMutationId: getTemporaryId(),
			estimationTime: timeToDuration(time.value),
			estimationSp: Number(sp.value),
			comment: props.activity.comment,
			activityType: activityType.value.id,
			activityAt: activityAt.value.toISOString(),
		},
	});
}

debouncedWatch([activityType, activityAt, time, sp], updateActivity, {
	debounce: 1000,
});
</script>
