<template>
	<Card class="flex flex-col">
		<div class="text-base text-blue-900 mb-4">Overall tasks completed</div>
		<div
			class="relative w-36 h-36 self-center grid items-center justify-items-center"
		>
			<SvgCircle
				:total="data.totalTask"
				:current="data.completedTask"
				:stroke-width="15"
				:diameter="144"
				class="row-span-full col-span-full"
			/>
			<div class="row-span-full col-span-full text-xl text-blue-800">
				<div class="justify-center items-center flex">
					<span class="text-3xl font-semibold">{{ data.completedTask }}</span>
					<span class="px-1">/</span>
					<span class="text-base">{{ data.totalTask }}</span>
				</div>
				<div class="text-sm text-gray-500 text-center">Tasks</div>
			</div>
		</div>
	</Card>
</template>
<script lang="ts">
import { defineComponent } from '@vue/runtime-core';
import SvgCircle from '../svg-circle/svg-circle.component.vue';
import Card from '../card/card.component.vue';

export default defineComponent({
	components: { SvgCircle, Card },
	props: {
		data: {
			type: Object,
			required: true,
		},
	},
});
</script>
