<template>
	<tr>
		<td class="w-[16rem] pl-12 py-0">
			<Select
				:model-value="activityType"
				:options="activityTypes"
				name-field="friendlyName"
				button-border=""
				@update:model-value="emit('update:activityType', $event)"
			/>
		</td>
		<td class="px-6 py-0">
			<Select
				:model-value="technology"
				:options="technologies"
				name-field="friendlyName"
				button-border=""
				@update:model-value="emit('update:technology', $event)"
			/>
		</td>
		<td class="py-0">
			<Select
				:model-value="repository"
				:options="repositories"
				name-field="friendlyName"
				button-border=""
				@update:model-value="emit('update:repository', $event)"
			/>
		</td>
		<td class="py-0 px-4">{{ phase }}</td>
		<td class="py-0 max-w-xs" :title="milestone">
			<div class="w-36 truncate">{{ milestone }}</div>
		</td>
		<td class="py-0 px-6">{{ sprint }}</td>
		<td class="px-2 py-0">
			<div class="flex items-center gap-1 justify-end">
				<DatepickerInput
					input-class="w-24 border-none"
					:model-value="new Date(createdDate)"
					:input-attr="{ readOnly: true }"
					is-required
					@update:model-value="emit('update:activityAt', $event)"
				/>
			</div>
		</td>
		<td class="px-2">
			<Input
				:model-value="estimationSp.toString()"
				type="number"
				input-class="w-[70px] w-light-grey text-center font-semibold ml-8"
				@update:model-value="emit('update:estimationSp', $event)"
			/>
		</td>
		<td class="px-4">
			<Input
				:model-value="formatDuration(estimationTime)"
				type="time"
				:input-class="[
					'w-[70px] w-light-grey text-center font-semibold',
					estimationTime > 0 ? 'border-w-vivid-green' : 'border-w-pink',
				]"
				@update:model-value="emit('update:estimationTime', $event)"
			/>
		</td>
		<td class="w-px">
			<button class="px-4 py-4" @click="emit('delete')">
				<CloseIcon class="w-6 h-6 text-w-pink" />
			</button>
		</td>
	</tr>
</template>

<script lang="ts" setup>
import { CloseIcon } from '@witcher/icons';
import { formatDuration } from '@witcher/utils/time';
import { Input, Select, DatepickerInput } from '@app/components';
import { PropType } from '@vue/runtime-core';

const emit = defineEmits([
	'update:activityType',
	'update:technology',
	'update:repository',
	'update:estimationTime',
	'update:estimationSp',
	'update:activityAt',
	'delete',
]);

defineProps({
	activityType: {
		type: Object,
		required: true,
	},
	activityTypes: {
		type: Array as PropType<ReadonlyArray<Record<string, unknown>>>,
		required: true,
	},
	technology: {
		type: Object,
		required: true,
	},
	technologies: {
		type: Array as PropType<any[]>,
		required: true,
	},
	repository: {
		type: Object,
		required: true,
	},
	repositories: {
		type: Array as PropType<any[]>,
		required: true,
	},
	phase: {
		type: String,
		required: true,
	},
	milestone: {
		type: String,
		required: true,
	},
	sprint: {
		type: String,
		required: true,
	},
	createdDate: {
		type: String,
		required: true,
	},
	estimationTime: {
		type: Number,
		required: true,
	},
	estimationSp: {
		type: Number,
		required: true,
	},
});
</script>
