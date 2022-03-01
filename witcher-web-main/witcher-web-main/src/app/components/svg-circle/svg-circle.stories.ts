import { Meta, Story } from '@storybook/vue3';
import { defineComponent } from '@vue/runtime-core';
import Component from './svg-circle.component.vue';

export default {
	title: 'Components / Svg Circle',
	component: Component,
} as Meta;

export const SvgCircle: Story = () => {
	return defineComponent({
		components: { SvgCircle: Component },
		template: `
      <div class='flex gap-6'>
        <div class='inline-grid items-center justify-items-center'>
          <SvgCircle :stroke-width='14' :diameter='210' :total='100' :current='65' class='row-span-full col-span-full' />
          <SvgCircle :stroke-width='14' :diameter='160' :total='100' :current='25' class='row-span-full col-span-full' foreground-color='text-amber-300' />
          <SvgCircle :stroke-width='14' :diameter='100' :total='200' :current='150' class='row-span-full col-span-full' foreground-color='text-w-vivid-green' />
          <div class='row-span-full col-span-full text-xl font-bold'>150h</div>
        </div>
        <div class='inline-grid items-center justify-items-center'>
          <SvgCircle :stroke-width='5' :diameter='100' :total='100' :current='65' class='row-span-full col-span-full' />
          <SvgCircle :stroke-width='10' :diameter='80' :total='100' :current='25' class='row-span-full col-span-full' foreground-color='text-amber-300' />
          <SvgCircle :stroke-width='5' :diameter='50' :total='200' :current='150' class='row-span-full col-span-full' foreground-color='text-w-vivid-green' />
        </div>
      </div>
    `,
	});
};
