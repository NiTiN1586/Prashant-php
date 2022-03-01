<template>
	<div class="rounded bg-white p-6 shadow">
		<h3 class="mb-4">Phase Overview</h3>
		<div class="flex gap-6">
			<div class="inline-grid items-center justify-items-center">
				<SvgCircle
					:stroke-width="18"
					:diameter="144"
					:current="32"
					class="col-span-full row-span-full"
				/>
				<div class="col-span-full row-span-full grid text-center">
					<span class="font-semibold text-2xl">32%</span>
					<span class="text-sm">Phase 1</span>
				</div>
			</div>
			<div>
				<div class="mb-3">Create a login page for witcher platform</div>
				<div class="text-sm flex items-center gap-3">
					<span class="inline-block w-2 h-2 bg-w-blue-deep rounded" />
					<span>Milestones done</span>
				</div>
				<div class="text-sm flex items-center gap-3">
					<span class="inline-block w-2 h-2" />
					<div>
						<span class="text-xl">12</span>
						<span class="mx-3">/</span>
						<span>55</span>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>
<script lang="ts">
import { defineComponent } from '@vue/runtime-core';
import SvgCircle from '../../../../components/svg-circle/svg-circle.component.vue';

export default defineComponent({
	components: { SvgCircle },
});
</script>
