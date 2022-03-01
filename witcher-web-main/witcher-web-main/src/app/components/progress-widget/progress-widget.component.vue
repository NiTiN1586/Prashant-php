<template>
	<div>
		<div
			:class="[items[0].avatar ? 'mb-4' : 'mb-8']"
			class="text-base font-medium text-blue-900"
		>
			{{ title }}
		</div>
		<div
			v-for="item in items"
			:key="item.title"
			:class="{ 'mb-4': !item.avatar }"
		>
			<Avatar
				v-if="item.avatar"
				:src="item.avatar"
				:height="38"
				:width="38"
				class="inline"
				:class="{ '-mb-10': item.avatar }"
				alt="Project avatar"
			/>
			<div
				:class="{ 'ml-12 -mt-2': item.avatar }"
				class="flex justify-between mb-2 text-sm text-blue-900 tracking-wide"
			>
				<span>{{ item.title }}</span>
				<span class="font-semibold">{{ item.value }}</span>
			</div>
			<Progress :class="{ 'ml-12': item.avatar }" :progress="item.progress" />
		</div>
	</div>
</template>

<script lang="ts">
import { defineComponent } from '@vue/runtime-core';
import { PropType } from 'vue';
import Avatar from '../avatar/avatar.component.vue';
import Progress from '../progress/progress.component.vue';

export default defineComponent({
	components: {
		Progress,
		Avatar,
	},
	props: {
		title: {
			type: String as PropType<string>,
			required: true,
		},
		items: {
			type: Array as PropType<
				Array<{
					title: string;
					value: string;
					progress: number;
					avatar?: string;
				}>
			>,
			default: () => [],
		},
	},
});
</script>
