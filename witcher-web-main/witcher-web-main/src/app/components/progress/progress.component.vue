<template>
	<div class="h-[7px] rounded-full bg-w-charts-background">
		<div
			role="progressbar"
			class="h-full rounded-full bg-w-vivid-green"
			:style="{ width: `${progress}%` }"
			:aria-valuenow="progress"
			aria-valuemin="0"
			aria-valuemax="100"
		/>
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
	},
});
</script>
