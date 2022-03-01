import { ref } from 'vue';
import { Meta, Story } from '@storybook/vue3';
import { defineComponent } from '@vue/runtime-core';
import Component from './view-type-button.component.vue';

export default {
	title: 'Components / View Type Button',
	component: Component,
} as Meta;

export const ViewTypeButton: Story = () => {
	return defineComponent({
		components: { ViewType: Component },
		setup() {
			const value = ref('list');

			return { value };
		},
		template: `
          <ViewType v-model='value' />
    `,
	});
};
