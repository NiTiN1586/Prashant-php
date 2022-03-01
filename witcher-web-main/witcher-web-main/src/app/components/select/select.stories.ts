import { Meta, Story } from '@storybook/vue3';
import { defineComponent } from '@vue/runtime-core';
import { ref } from 'vue';
import Component from './select.component.vue';

export default {
	title: 'Components / Select',
	component: Component,
} as Meta;

export const Select: Story = () => {
	return defineComponent({
		components: { Select: Component },
		setup() {
			const people = [
				{ slug: 'slug-1', friendlyName: 'Wade Cooper' },
				{ slug: 'slug-2', friendlyName: 'Arlene Mccoy' },
				{ slug: 'slug-3', friendlyName: 'Devon Webb' },
				{ slug: 'slug-4', friendlyName: 'Tom Cook' },
				{ slug: 'slug-5', friendlyName: 'Tanya Fox' },
				{ slug: 'slug-6', friendlyName: 'Hellen Schmidt' },
			];

			const selectedPerson = ref(people[1]);

			return { people, selectedPerson };
		},
		template: `
      <div class="h-screen flex items-start mt-10 ml-10 w-72">
        <Select
          button-border='border'
          :options='people'
          v-model='selectedPerson'
          id-field='slug'
          name-field='friendlyName'
        />
      </div>
    `,
	});
};
