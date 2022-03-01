import { Meta, Story } from '@storybook/vue3';
import { defineComponent } from '@vue/runtime-core';
import { ref } from 'vue';
import Component from './toggle.component.vue';

export default {
	title: 'Components / Toggle',
	component: Component,
} as Meta;

export const Toggle: Story = () => {
	return defineComponent({
		components: { Toggle: Component },
		setup() {
			const checked1 = ref(false);
			const checked2 = ref(false);
			const checked3 = ref(false);
			return { checked1, checked2, checked3 };
		},
		template: `
      <div class='grid gap-3'>
      <Toggle v-model='checked1' label='Toggle 1' :background-color="'bg-gray-200'" />
      <Toggle v-model='checked2' label='Toggle 2' :background-color="'bg-gray-200'" />
      <Toggle v-model='checked3' label='Toggle 3' :background-color="'bg-gray-200'" />
      </div>
    `,
	});
};
