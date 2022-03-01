import { Meta, Story } from '@storybook/vue3';
import { defineComponent } from '@vue/runtime-core';
import * as iconsModule from './index';

const icons = Object.entries(iconsModule);

export default {
	title: 'Icons / Witcher',
} as Meta;

const colors = [
	'text-w-dark-blue',
	'text-w-grey-icons',
	'text-w-light-grey',
	'text-w-purple',
	'text-w-orange',
	'text-w-vivid-green',
	'text-w-gradient-button-1',
	'text-w-gradient-button-2',
	'text-w-blue-deep',
	'text-w-pink',
];

export const Witcher: Story = () => {
	return defineComponent({
		icons,
		colors,
		template: `
      <div class='flex flex-wrap gap-3'>
        <div
          v-for='([name, icon], index) in $options.icons'
          :class='$options.colors[index % $options.colors.length]'
          class='p-3 inline-flex gap-3 items-center border border-current relative bg-white'>
          <div class='absolute inset-0 shadow-mood shadow-current opacity-30 z-[-1]' />
          <component :is='icon' class='w-7 h-7 border border-dashed border-current' />
          <span>{{ name }}</span>
        </div>
      </div>
    `,
	});
};
