import { Meta, Story } from '@storybook/vue3';
import { defineComponent } from '@vue/runtime-core';
import { AddCircleIcon } from '@witcher/icons';
import Component from './button.component.vue';

type Args = {
	variant: 'contained' | 'outlined';
	color: 'blue' | 'danger' | 'green';
};

export default {
	title: 'Components / Button',
	component: Component,
	// TODO: Find a way to make Storybook understand TypeScript interface
	argTypes: {
		variant: {
			options: ['contained', 'outlined'],
		},
		color: {
			options: ['blue', 'danger', 'green'],
		},
	},
} as Meta<Args>;

export const Button: Story<Args> = (_, { argTypes }) => {
	return defineComponent({
		components: { Button: Component, AddCircleIcon },
		props: Object.keys(argTypes),
		template: `
      <div class='flex gap-3'>
      <Button :variant='variant' :color='color'>
        <template #icon-right>
          <AddCircleIcon class='w-7 h-7' />
        </template>
        Add project
      </Button>
      <Button :variant='variant' :color='color'>Do something good!</Button>
      </div>
    `,
	});
};
