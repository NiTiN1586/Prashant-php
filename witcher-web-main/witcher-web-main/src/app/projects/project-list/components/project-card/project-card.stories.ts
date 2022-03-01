import { Meta, Story } from '@storybook/vue3';
import { defineComponent } from '@vue/runtime-core';
import Component from './project-card.component.vue';

export default {
	title: 'Components / Project Card',
	component: Component,
} as Meta;

const project = {
	node: {
		id: '/api/witcher_projects/AC',
		name: 'Academy',
	},
};

export const ProjectCard: Story = () => {
	return defineComponent({
		project,
		components: { Card: Component },
		template: `
      <div class="flex w-auto flex-col md:flex-row">
      <Card :project='$options.project' class="mb-4 md:mb-0 md:mr-5 md:w-1/2" />
      <Card :project='$options.project' class="md:w-1/2" />
      </div>
    `,
	});
};
