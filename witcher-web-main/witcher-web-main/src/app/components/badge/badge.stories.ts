import { Meta, Story } from '@storybook/vue3';
import { defineComponent } from '@vue/runtime-core';
import Component from './badge.component.vue';

export default {
	title: 'Components / Badge',
	component: Component,
} as Meta;

export const Badge: Story = () => {
	return defineComponent({
		components: { Badge: Component },
		setup() {
			const value = '12';

			return { value };
		},
		template: `
          <Badge>{{value}}</Badge>
    `,
	});
};
