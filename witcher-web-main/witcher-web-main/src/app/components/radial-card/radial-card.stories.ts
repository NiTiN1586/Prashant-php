import { Meta, Story } from '@storybook/vue3';
import { defineComponent } from '@vue/runtime-core';
import Component from './radial-card.component.vue';

export default {
	title: 'Components / Radial Card',
	component: Component,
} as Meta;

export const RadialCard: Story = () => {
	return defineComponent({
		components: { RadialCard: Component },
		setup() {
			return { data };
		},
		template: `
      <div class='grid gap-5 grid-cols-1 sm:grid-cols-2 lg:grid-cols-4'>
      <RadialCard :data="data"/>
      <RadialCard :data="data"/>
      <RadialCard :data="data"/>
      <RadialCard :data="data"/>
      </div>
    `,
	});
};
const data = {
	totalTask: 375,
	completedTask: 20,
};
