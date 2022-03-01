import { Meta, Story } from '@storybook/vue3';
import { defineComponent } from '@vue/runtime-core';
import Component from './sort-button.component.vue';

export default {
	title: 'Components / Sort Button',
	component: Component,
} as Meta;

export const SortButton: Story = () => {
	return defineComponent({
		components: { SortButton: Component },
		setup() {
			const columns = [
				'Activity/project name',
				'Tech',
				'Source',
				'Phase',
				'Milestone',
				'Sprint',
				'Creation',
				'SP',
				'Hours',
			];

			return { columns };
		},
		template: `
      <table class='w-full'>
      <thead>
      <tr>
        <th v-for='column in columns' :key='column'>
          <SortButton>{{ column }}</SortButton>
        </th>
      </tr>
      </thead>
      </table>
    `,
	});
};
