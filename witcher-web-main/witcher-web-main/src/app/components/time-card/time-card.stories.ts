import { Meta, Story } from '@storybook/vue3';
import { defineComponent } from '@vue/runtime-core';
import Component from './time-card.component.vue';

export default {
	title: 'Components / Time Card',
	component: Component,
} as Meta;

export const TimeCard: Story = () => {
	return defineComponent({
		components: { TimeCard: Component },
		template: `
      <div class='flex flex-wrap gap-5'>
      <TimeCard />
      <TimeCard />
      <TimeCard />
      <TimeCard />
      </div>
    `,
	});
};
