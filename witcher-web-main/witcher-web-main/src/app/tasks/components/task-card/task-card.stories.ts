import { Meta, Story } from '@storybook/vue3';
import { defineComponent } from '@vue/runtime-core';
import Component from './task-card.component.vue';

export default {
	title: 'Components / Task Card',
	component: Component,
	parameters: { layout: 'none' },
} as Meta;

export const TaskCard: Story = () => {
	return defineComponent({
		components: { Card: Component },
		template: `
      <div class="grid grid-cols-3 gap-4 h-screen p-4">
        <div class='bg-gray-50 p-4 space-y-4'>
          <h3 class='font-bold text-gray-600'>To Do</h3>
          <Card />
        </div>
        <div class='bg-gray-50 p-4 space-y-4'>
          <h3 class='font-bold text-gray-600'>In Progress</h3>
          <Card />
          <Card />
        </div>
        <div class='bg-gray-50 p-4 space-y-4'>
          <h3 class='font-bold text-gray-600'>Done</h3>
          <Card />
        </div>
      </div>
    `,
	});
};
