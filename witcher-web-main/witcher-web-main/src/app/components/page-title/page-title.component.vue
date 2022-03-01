<template>
	<h1 class="text-lg font-medium">
		<Badge v-if="count">
			{{ count }}
		</Badge>
		{{ title }}
	</h1>
</template>

<script lang="ts">
import { defineComponent } from '@vue/runtime-core';
import { PropType } from 'vue';
import Badge from '../badge/badge.component.vue';

export default defineComponent({
	components: { Badge },
	props: {
		title: {
			required: true,
			type: String as PropType<string>,
		},
		count: {
			type: [Number, String] as PropType<number | string>,
			default: 0,
		},
	},
});
</script>
