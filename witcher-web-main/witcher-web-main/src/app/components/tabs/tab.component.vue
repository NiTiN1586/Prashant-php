<template>
	<div
		class="text-sm py-3 cursor-pointer -mb-px border-b-[3px]"
		:class="{
			'text-w-pink': important,
			'border-w-blue-deep text-w-blue-deep font-semibold': selected,
			'border-transparent': !selected,
		}"
	>
		<span>{{ name }}</span>
		<span
			v-if="count > 0"
			:class="[
				selected
					? 'text-white bg-w-blue-deep'
					: 'text-w-blue-deep bg-w-charts-background',
			]"
			class="ml-4 rounded-lg px-2 py-1 font-semibold"
		>
			{{ count }}
		</span>
	</div>
</template>

<script lang="ts">
import { defineComponent } from '@vue/runtime-core';
import { PropType } from 'vue';

export default defineComponent({
	props: {
		name: {
			type: String as PropType<string>,
			required: true,
		},
		selected: {
			type: Boolean as PropType<boolean>,
			default: false,
		},
		important: {
			type: Boolean as PropType<boolean>,
			default: false,
		},
		count: {
			type: Number as PropType<number>,
			default: 0,
		},
	},
});
</script>
