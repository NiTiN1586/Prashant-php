<template>
	<div class="flex flex-wrap p-4 gap-3 my-3 items-center justify-end">
		<Select v-model="selectedTech" class="w-48 w-light-grey" :options="tech" />
		<Select
			v-model="selectedClient"
			class="w-48 w-light-grey"
			:options="client"
		/>
		<Select
			v-model="selectedProject"
			class="w-48 w-light-grey"
			:options="project"
		/>
		<Select
			v-model="selectedSprint"
			class="w-48 w-light-grey"
			:options="sprint"
		/>
		<Select v-model="selectedSort" class="w-48 w-light-grey" :options="sort" />
		<slot />
	</div>
</template>

<script lang="ts">
import { ref } from 'vue';
import { defineComponent } from '@vue/runtime-core';
import Select from '../../../../components/select/select.component.vue';

export default defineComponent({
	components: { Select },
	setup() {
		const tech = [
			{ name: 'Technologies' },
			{ name: 'ReactJS' },
			{ name: 'VueJS' },
			{ name: 'Vite' },
			{ name: 'NextJS' },
			{ name: 'NuxtJS' },
		];
		const sprint = [
			{ name: 'Sprints' },
			{ name: 'Sprint1' },
			{ name: 'Sprint2' },
			{ name: 'Sprint3' },
			{ name: 'Sprint4' },
			{ name: 'Sprint5' },
		];
		const project = [
			{ name: 'All Projects' },
			{ name: 'project1' },
			{ name: 'project2' },
			{ name: 'project3' },
			{ name: 'project4' },
			{ name: 'project5' },
		];
		const client = [
			{ name: 'All Clients' },
			{ name: 'client1' },
			{ name: 'client2' },
			{ name: 'client3' },
			{ name: 'client4' },
			{ name: 'client5' },
		];
		const sort = [
			{ name: 'sort by' },
			{ name: 'ascending' },
			{ name: 'descending' },
			{ name: 'date' },
			{ name: 'status' },
			{ name: 'progress' },
		];

		const selectedTech = ref(tech[0]);
		const selectedSprint = ref(sprint[0]);
		const selectedProject = ref(project[0]);
		const selectedClient = ref(client[0]);
		const selectedSort = ref(sort[0]);

		return {
			tech,
			selectedTech,
			sprint,
			selectedSprint,
			project,
			selectedProject,
			client,
			selectedClient,
			sort,
			selectedSort,
			Select,
		};
	},
});
</script>
