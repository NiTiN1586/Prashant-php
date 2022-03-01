import { Meta, Story } from '@storybook/vue3';
import { defineComponent } from '@vue/runtime-core';
import { ref } from 'vue';
import { Container } from '../../../components';
import Component from './task-row-item.component.vue';
import Header from './task-row-header.component.vue';

declare interface Task {
	id: string;
	name: string;
	project: string;
	phase: string;
	milestone: string;
	sprint: string;
	storyPoints: string;
	efficiency: string;
	witcherProject: any;
}

export default {
	title: 'Components / Task Row',
	component: Component,
} as Meta;

export const TaskRow: Story = () => {
	return defineComponent({
		components: { TaskRow: Component, Header, Container },
		setup() {
			const items = ref(
				tasks.map((t) => {
					return { ...t, checked: false };
				}),
			);

			function onChecked(id: string) {
				items.value = items.value.map((t) => {
					return {
						...t,
						checked: t.id === id,
					};
				});
			}

			return { items, onChecked };
		},
		template: `
      <Container fluid class='overflow-x-auto'>
      <table class='min-w-full table-fixed' style='border-spacing: 0 10px; border-collapse: separate'>
        <thead>
        <Header />
        </thead>
        <tbody>
        <TaskRow v-for='task in items' :key='task.id' :task='task' @check='onChecked(task.id)' />
        </tbody>
      </table>
      </Container>`,
	});
};

const witcherProject = { id: '42' };

const tasks: Task[] = [
	{
		id: 'TUI-122',
		name: 'Change button on home page',
		project: 'TUI',
		phase: '2',
		milestone: 'Build this VIP project',
		sprint: 'Sprint 5',
		storyPoints: '16',
		efficiency: '20%',
		witcherProject,
	},
	{
		id: 'TUI-123',
		name: 'Change button on home page',
		project: 'TUI',
		phase: '2',
		milestone: 'Build this VIP project',
		sprint: 'Sprint 5',
		storyPoints: '16',
		efficiency: '20%',
		witcherProject,
	},
	{
		id: 'TUI-124',
		name: 'Change button on home page',
		project: 'TUI',
		phase: '2',
		milestone: 'Build this VIP project',
		sprint: 'Sprint 5',
		storyPoints: '16',
		efficiency: '20%',
		witcherProject,
	},
	{
		id: 'TUI-125',
		name: 'Change button on home page',
		project: 'TUI',
		phase: '2',
		milestone: 'Build this VIP project',
		sprint: 'Sprint 5',
		storyPoints: '16',
		efficiency: '20%',
		witcherProject,
	},
	{
		id: 'TUI-126',
		name: 'Change button on home page',
		project: 'TUI',
		phase: '2',
		milestone: 'Build this VIP project',
		sprint: 'Sprint 5',
		storyPoints: '16',
		efficiency: '20%',
		witcherProject,
	},
	{
		id: 'TUI-127',
		name: 'Change button on home page',
		project: 'TUI',
		phase: '2',
		milestone: 'Build this VIP project',
		sprint: 'Sprint 5',
		storyPoints: '16',
		efficiency: '20%',
		witcherProject,
	},
	{
		id: 'TUI-128',
		name: 'Change button on home page',
		project: 'TUI',
		phase: '2',
		milestone: 'Build this VIP project',
		sprint: 'Sprint 5',
		storyPoints: '16',
		efficiency: '20%',
		witcherProject,
	},
	{
		id: 'TUI-129',
		name: 'Change button on home page',
		project: 'TUI',
		phase: '2',
		milestone: 'Build this VIP project',
		sprint: 'Sprint 5',
		storyPoints: '16',
		efficiency: '20%',
		witcherProject,
	},
	{
		id: 'TUI-130',
		name: 'Change button on home page',
		project: 'TUI',
		phase: '2',
		milestone: 'Build this VIP project',
		sprint: 'Sprint 5',
		storyPoints: '16',
		efficiency: '20%',
		witcherProject,
	},
];
