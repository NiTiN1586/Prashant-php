<template>
	<section class="border-t-4 border-w-blue-deep shadow rounded-lg p-4 relative">
		<div class="flex flex-col text-blue-900">
			<div class="flex justify-between items-start">
				<div class="border-2 border-gray-200 rounded">
					<img
						alt="project image"
						:src="getPicsumImage(20, 100, 100)"
						class="w-9 h-9 object-cover rounded-full inline-block m-2"
					/>
				</div>
				<div class="flex gap-3 items-center">
					<div class="flex bg-gray-100 px-2 py-1 rounded-lg">
						<span
							class="w-2.5 h-2.5 inline-block rounded-full bg-blue-700 mr-1 my-1.5"
						/>
					</div>
					<div class="flex">
						<StoryPoints />
						<span class="text-sm font-normal my-auto">500</span>
					</div>
				</div>
			</div>
			<RouterLink
				class="after:absolute after:inset-0 text-base font-normal"
				:to="{ name: 'project-details', params: { id: project.node.id } }"
			>
				{{ project.node.name }}
			</RouterLink>
			<div
				class="flex mt-2 mb-5 font-light flex-wrap text-w-grey-icons text-sm"
			>
				<div class="flex items-baseline">
					<span class="mr-2">Project Parent</span>
					<ExternalLink />
				</div>
				<span class="mx-2">|</span>
				<div class="flex items-baseline">
					<span class="mr-2">Client name/ref</span>
					<ExternalLink />
				</div>
			</div>
			<div class="flex justify-between">
				<div class="flex justify-start -space-x-4">
					<Avatar
						v-for="i in 3"
						:key="i"
						class="border-2 border-white"
						alt="developer image"
						:src="getPicsumImage(i + 1, 100, 100)"
					/>
					<div
						class="flex w-9 h-9 rounded-full border-2 border-gray-200 bg-white justify-center items-center"
					>
						+6
					</div>
				</div>
				<div class="pl-3 ml-12 flex items-center">
					<span class="mr-2 text-xs">Lead</span>
					<Avatar alt="lead image" :src="getPicsumImage(3, 100, 100)" />
				</div>
			</div>
			<div class="mt-4">
				<div class="flex justify-between text-sm">
					<div class="font-normal text-w-dark-blue">
						Progress (<span class="text-w-vivid-green font-medium">450 </span
						>/500)
					</div>
					<div class="font-medium">62%</div>
				</div>
				<div
					class="overflow-hidden h-2 mb-4 text-xs flex rounded-full bg-gray-100"
				>
					<div
						style="width: 62%"
						class="flex flex-col text-center whitespace-nowrap text-white justify-center bg-w-vivid-green rounded-full"
					></div>
				</div>
				<div class="overflow-hidden h-2 text-xs flex rounded-full bg-gray-100">
					<div
						style="width: 32%"
						class="flex flex-col text-center whitespace-nowrap text-white justify-center bg-w-blue-deep rounded-full"
					></div>
				</div>
				<div class="flex justify-between text-sm">
					<div class="font-normal">Efficiency</div>
					<div class="font-medium">32%</div>
				</div>
			</div>
			<div class="text-gray-200 my-4">
				<hr class="border-dashed" />
			</div>
			<div class="flex justify-between items-center text-sm font-normal">
				<div
					class="flex items-center px-2 py-1 text-w-vivid-green bg-w-vivid-green/5 rounded-lg"
				>
					<Correct /> <span class="ml-1">In progress</span>
				</div>
				<div class="flex">
					<Timesheet />
					<span class="ml-0.5 my-auto">Nov 23, 2021</span>
				</div>
			</div>
		</div>
	</section>
</template>

<script lang="ts">
import { defineComponent } from '@vue/runtime-core';
import { RouterLink } from 'vue-router';
import { Avatar } from '@app/components';
import { getPicsumImage } from '@witcher/utils/get-picsum-image';
import StoryPoints from './components/story-points.component.vue';
import ExternalLink from './components/external-link.component.vue';
import Timesheet from './components/timesheet.component.vue';
import Correct from './components/correct.component.vue';

export default defineComponent({
	components: {
		StoryPoints,
		ExternalLink,
		Timesheet,
		Correct,
		Avatar,
		RouterLink,
	},
	props: {
		project: {
			type: Object,
			default: () => {},
		},
	},
	setup: () => ({ getPicsumImage }),
});
</script>
