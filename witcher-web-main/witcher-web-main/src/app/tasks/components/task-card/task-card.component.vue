<template>
	<div class="border-t-4 border-pink-600 shadow rounded-lg pt-0 pl-0 pb-4 pr-4">
		<div
			class="bg-pink-600 w-auto text-white px-3 py-2 w-max rounded-br-lg t-0"
		>
			Urgent
		</div>
		<div class="flex flex-col text-blue-900 pt-2 px-3">
			<div class="flex justify-between items-center">
				<div class="rounded">
					<img
						alt="project image"
						:src="getPicsumImage(20, 100, 100)"
						class="w-9 h-9 object-cover rounded-full inline-block mr-2 my-2"
					/>
					WIT-10025
				</div>
				<div
					class="flex gap-3 items-center text-yellow-500 bg-yellow-50 px-3 py-2 rounded-lg"
				>
					<CareTrace />
					<span>10 %</span>
				</div>
			</div>
			<div class="pt-3">[Witcher+jagaad-api] Improve user auth process</div>
			<div class="flex justify-between items-center pt-3">
				<div class="flex items-center py-1 rounded-lg">
					<div class="flex gap-3 items-center">
						<div class="flex text-green-400 bg-green-50 px-3 py-1 rounded-lg">
							<Sprint />
							<span class="pl-1">5</span>
						</div>
						<div class="flex">
							<StoryPoints />
							<span>16 sp</span>
						</div>
					</div>
				</div>
				<div class="flex">
					<div class="pl-3 ml-12 flex items-center">
						<span class="mr-2">assignee</span>
						<Avatar alt="lead image" :src="getPicsumImage(3, 100, 100)" />
					</div>
				</div>
			</div>
			<div class="text-gray-200 my-4">
				<hr class="border-dashed" />
			</div>
			<div class="flex justify-between items-center">
				<div class="flex items-center px-2 py-1 bg-gray-100 rounded-lg">
					<span
						class="w-2.5 h-2.5 inline-block rounded-full bg-blue-700 mr-1 my-1.5"
					/>
					Factory Project
				</div>
				<div class="flex">
					<Timesheet />
					<span class="ml-0.5">Nov 23, 2021</span>
				</div>
			</div>
		</div>
	</div>
</template>

<script lang="ts">
import { defineComponent } from '@vue/runtime-core';
import { getPicsumImage } from '../../../../utils/get-picsum-image';
import { Avatar } from '../../../components';
import StoryPoints from './components/story-points.component.vue';
import Timesheet from './components/timesheet.component.vue';
import Sprint from './components/sprint.component.vue';
import CareTrace from './components/car-traced.component.vue';

export default defineComponent({
	components: { StoryPoints, Timesheet, Avatar, Sprint, CareTrace },
	setup: () => ({ getPicsumImage }),
});
</script>
