<template>
	<Container fluid class="overflow-x-auto px-0">
		<table
			class="min-w-full table-fixed"
			style="border-spacing: 0 10px; border-collapse: separate"
		>
			<thead>
				<ProjectHeader />
			</thead>
			<tbody>
				<ProjectRow
					v-for="project in results.witcherProjects.edges"
					:key="project.node.id"
					:project="{
						name: project.node.name,
						slug: project.node.id,
						type: project.projectType?.businessBranch?.friendlyName,
						efficiency: 50,
						progress: 60,
						points: 2000,
						phase: 'Phase ',
						image: getPicsumImage(1, 100, 100),
						manager: {
							name: 'Jo Askin',
							image: getPicsumImage(2, 100, 100),
						},
						leader: {
							name: 'Jo Askin',
							image: getPicsumImage(3, 100, 100),
						},
						client: {
							name: 'Jo Askin',
							image: getPicsumImage(4, 100, 100),
						},
					}"
				/>
			</tbody>
		</table>
	</Container>
</template>
<script lang="ts">
import { defineComponent } from '@vue/runtime-core';
import { Container } from '@witcher/components';
import { getPicsumImage } from '../../../../../utils/get-picsum-image';
import ProjectRow from '../project-row/project-row-item.component.vue';
import ProjectHeader from '../project-row/project-row-header.component.vue';

export default defineComponent({
	components: { ProjectRow, ProjectHeader, Container },
	props: {
		results: {
			type: Object,
			default: () => {},
		},
	},
	setup: () => ({ getPicsumImage }),
});
</script>
