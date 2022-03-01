import { Meta, Story } from '@storybook/vue3';
import { defineComponent } from '@vue/runtime-core';
import Component from './progress-widget.component.vue';

export default {
	title: 'Components / Progress Widget',
	component: Component,
} as Meta;

export const ProgressWidget: Story = () => {
	return defineComponent({
		components: { ProgressWidget: Component },
		items,
		template: `
      <ProgressWidget title='Most invested projects' :items='$options.items' />
    `,
	});
};

const items = [
	{ title: 'Development', value: '13:05 Hrs', progress: 10 },
	{ title: 'Meeting', value: '15:05 Hrs', progress: 50 },
	{ title: 'UI/UX Design', value: '23:05 Hrs', progress: 30 },
];
