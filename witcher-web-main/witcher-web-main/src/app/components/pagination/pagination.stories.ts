import { Meta, Story } from '@storybook/vue3';
import { defineComponent } from '@vue/runtime-core';
import Component from './pagination.component.vue';

export default {
	title: 'Components / Pagination',
	component: Component,
} as Meta;

export const Pagination: Story = () => {
	return defineComponent({
		components: { Pagination: Component },
		data: () => ({
			currentPage: 8,
		}),
		template: `
      <Pagination v-model:current-page='currentPage' :last-page='20' />
    `,
	});
};
