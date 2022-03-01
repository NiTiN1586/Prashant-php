<template>
	<tr
		class="group whitespace-nowrap text-blue-900 text-sm hover:bg-gray-50 cursor-pointer relative"
	>
		<td
			class="border-t border-b border-l rounded-l p-3 truncate border-w-light-grey"
		>
			<div class="flex items-center">
				<div
					class="border-l-4 rounded-full h-9 mr-4 border-w-charts-background"
					:class="{ 'border-w-orange': task.checked }"
				/>
				<label class="cursor-pointer">
					<input
						type="checkbox"
						name="selected-task"
						:value="task.checked"
						class="sr-only"
						@input="$emit('check', $event)"
					/>
					<span
						class="w-5 h-5 block rounded-full border border-w-light-grey"
						:class="{ 'bg-blue-500 border-w-light-grey': task.checked }"
					/>
				</label>
				<RouterLink
					:to="{ name: 'task-details', params: { id: task.id } }"
					class="ml-2 flex items-center"
				>
					<span class="font-semibold"> {{ task.slug }}</span>
					<div class="mx-2 w-1 h-1 rounded-full bg-blue-900"></div>
					<span class="truncate max-w-xs">{{ task.summary }}</span>
				</RouterLink>
			</div>
		</td>
		<td class="border-w-light-grey border-t border-b py-3 px-3 truncate">
			<RouterLink
				:to="{
					name: 'project-details',
					params: { id: task.witcherProject.id },
				}"
				class="flex items-center"
			>
				<span class="mr-3">{{ task.witcherProject.name }}</span>
				<ExternalLinkIcon class="w-4 h-4" />
			</RouterLink>
		</td>
		<td class="border-w-light-grey border-t border-b py-3 px-3 truncate">
			{{ task.phase }} Phase
		</td>

		<td class="border-w-light-grey border-t border-b py-3 px-3 truncate">
			{{ task.milestone }}
		</td>
		<td class="border-w-light-grey border-t border-b py-3 px-3 truncate">
			{{ task.sprint }}
		</td>
		<td
			class="border-w-light-grey border-t border-b py-3 px-3 truncate font-semibold"
		>
			{{ task.storyPoints }} SP
		</td>
		<td
			class="border-w-light-grey border-t border-b border-r rounded-r p-3 truncate font-semibold"
		>
			{{ task.efficiency }}
		</td>
	</tr>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import { ExternalLinkIcon } from '@witcher/icons';
import { RouterLink } from 'vue-router';

export default defineComponent({
	components: {
		ExternalLinkIcon,
		RouterLink,
	},
	props: {
		task: {
			type: Object,
			required: true,
		},
	},
	emits: ['check'],
});
</script>
