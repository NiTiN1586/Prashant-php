<template>
	<div class="flex items-center gap-4 overflow-hidden">
		<div>
			<slot />
		</div>
		<div class="overflow-hidden">
			<div class="truncate text-xl text-blue-800 font-medium mb-1">
				{{ title }}
				<small>{{ afterTitle }}</small>
			</div>
			<div class="text-gray-400 tracking-wide text-sm">
				{{ subtitle }}
			</div>
		</div>
	</div>
</template>

<script lang="ts">
import { defineComponent } from '@vue/runtime-core';
import { PropType } from 'vue';

export default defineComponent({
	props: {
		title: {
			type: String as PropType<string>,
			required: true,
		},
		afterTitle: {
			type: String as PropType<string>,
			default: '',
		},
		subtitle: {
			type: String as PropType<string>,
			required: true,
		},
	},
});
</script>
