<template>
	<div
		role="progressbar"
		:class="[size, backgroundColor]"
		class="rounded-full"
		:style="{ width: `${progress}%` }"
		:aria-valuenow="progress"
		aria-valuemin="0"
		aria-valuemax="100"
	>
		<div :class="indicatorClasses" class="h-2 w-2 ml-auto rounded-full"></div>
		<div
			class="text-[18px] font-medium text-w-dark-blue float-right -mr-24 -mt-4"
		>
			{{ label }}
			<span class="text-[13px]">({{ progress }}%)</span>
		</div>
	</div>
</template>

<script lang="ts">
import { defineComponent } from '@vue/runtime-core';
import { PropType } from 'vue';

export default defineComponent({
	props: {
		progress: {
			type: Number as PropType<number>,
			required: true,
		},
		label: {
			type: String as PropType<string>,
			required: true,
		},
		size: {
			type: String as PropType<string>,
			default: 'h-[38px]',
		},
		backgroundColor: {
			type: String as PropType<string>,
			default: 'bg-w-blue-deep',
		},
		indicatorClasses: {
			type: String as PropType<string>,
			default: 'bg-white mt-4 mr-3',
		},
	},
});
</script>
