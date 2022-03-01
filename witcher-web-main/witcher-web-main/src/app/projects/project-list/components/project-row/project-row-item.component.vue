<template>
	<tr class="whitespace-nowrap text-sm hover:bg-gray-50 relative">
		<td
			class="border-t border-b border-l rounded-l-lg py-3 pl-12 pr-3 truncate"
		>
			<Avatar alt="project image" :src="project.image" />
			<RouterLink
				class="ml-2 after:absolute after:inset-0"
				:to="{ name: 'project-details', params: { id: project.slug } }"
			>
				{{ project.name }}
			</RouterLink>
		</td>
		<td class="border-t border-b py-3 px-3 truncate">
			{{ project.type ? project.type : 'None' }}
		</td>
		<td class="border-t border-b py-3 px-3 truncate">
			<Avatar alt="manager image" :src="project.manager.image" />
			<span class="ml-2">{{ project.manager.name }}</span>
		</td>
		<td class="border-t border-b py-3 px-3 truncate">
			<Avatar alt="leader image" :src="project.leader.image" />
			<span class="ml-2">
				{{ project.leader.name }}
			</span>
		</td>
		<td class="border-t border-b py-3 px-3 truncate">
			<Avatar alt="client image" :src="project.client.image" />
		</td>
		<td class="border-t border-b py-3 px-3 truncate">
			{{ project.points }}
		</td>
		<td class="border-t border-b py-3 px-3 truncate">
			{{ project.efficiency }}%
		</td>
		<td class="border-t border-b py-3 px-3 truncate">
			{{ project.phase }}
		</td>
		<td
			class="border-t border-b border-r rounded-r-lg py-3 pl-3 pr-12 truncate"
		>
			{{ project.progress }}%
		</td>
	</tr>
</template>

<script lang="ts">
import { defineComponent } from '@vue/runtime-core';
import { PropType } from 'vue';
import { RouterLink } from 'vue-router';
import { Avatar } from '@app/components';

export default defineComponent({
	components: { Avatar, RouterLink },
	props: {
		project: {
			type: Object as PropType<any>,
			required: true,
		},
	},
});
</script>
