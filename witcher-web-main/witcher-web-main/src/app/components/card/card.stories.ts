import { Meta, Story } from '@storybook/vue3';
import { defineComponent } from '@vue/runtime-core';
import Component from './card.component.vue';

export default {
	title: 'Components / Card',
	component: Component,
} as Meta;

export const Card: Story = () =>
	defineComponent({
		components: { Card: Component },
		template: `
      <div class='grid gap-6 grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5'>
      <Card>Lorem ipsum dolor sit amet.</Card>
      <Card>Lorem ipsum dolor sit amet.</Card>
      <Card>Lorem ipsum dolor sit amet.</Card>
      <Card>Lorem ipsum dolor sit amet.</Card>
      <Card>Lorem ipsum dolor sit amet.</Card>
      <Card>Lorem ipsum dolor sit amet.</Card>
      <Card>Lorem ipsum dolor sit amet.</Card>
      <Card>Lorem ipsum dolor sit amet.</Card>
      </div>
    `,
	});
