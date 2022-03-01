<template>
	<div
		class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4"
	>
		<div
			v-for="project in results.witcherProjects.edges"
			:key="project.node.id"
		>
			<ProjectCard class="m-4" :project="project" />
		</div>
	</div>
</template>

<script lang="ts">
import { defineComponent } from '@vue/runtime-core';
import ProjectCard from '../project-card/project-card.component.vue';

export default defineComponent({
	components: { ProjectCard },
	props: {
		results: {
			type: Object,
			default: () => {},
		},
	},
});
</script>
