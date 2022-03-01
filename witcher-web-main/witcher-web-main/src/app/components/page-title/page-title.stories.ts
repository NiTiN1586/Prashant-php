import { Meta, Story } from '@storybook/vue3';
import { defineComponent } from '@vue/runtime-core';
import Component from './page-title.component.vue';

export default {
	title: 'Components / Page Title',
} as Meta;

export const PageTitle: Story = () => {
	return defineComponent({
		components: { PageTitle: Component },
		template: `
      <div class='grid gap-3'>
      <PageTitle title='Weekly tasks' count='15' />
      <PageTitle title='Weekly tasks list' count='2' />
      <PageTitle title='Weekly tasks' />
      </div>
    `,
	});
};
