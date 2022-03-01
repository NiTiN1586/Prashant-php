import { Meta, Story } from '@storybook/vue3';
import { defineComponent } from '@vue/runtime-core';
import Component from './labeled-progress.component.vue';

export default {
	title: 'Components / Labeled Progress',
	component: Component,
} as Meta;

export const LabeledProgress: Story = () => {
	return defineComponent({
		components: { LabeledProgress: Component },
		template: `
      <div class='grid gap-3'>
      <LabeledProgress :progress='i * 5' v-for='i in 10' :key='i' :label='(i * 5)' />
      </div>
    `,
	});
};
