import { Meta, Story } from '@storybook/vue3';
import { defineComponent } from '@vue/runtime-core';
import Component from './progress.component.vue';

export default {
	title: 'Components / Progress',
	component: Component,
} as Meta;

export const Progress: Story = () => {
	return defineComponent({
		components: { Progress: Component },
		template: `
      <div class='grid gap-3'>
      <Progress :progress='i * 10' v-for='i in 10' :key='i' />
      </div>
    `,
	});
};
