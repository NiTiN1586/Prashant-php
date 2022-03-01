import { Meta, Story } from '@storybook/vue3';
import { defineComponent } from '@vue/runtime-core';
import { h } from 'vue';
import { ForwardIcon } from '@witcher/icons';
import Component from './breadcrumbs.component.vue';

export default {
	title: 'Components / Breadcrumbs',
	component: Component,
} as Meta;

const separator = h(ForwardIcon, { class: 'w-6 h-6 text-w-grey-icons' });

export const Breadcrumbs: Story = () =>
	defineComponent({
		components: { Breadcrumbs: Component },
		setup: () => ({ separator }),
		template: `
      <Breadcrumbs :separator='separator'>
        <!-- Added v-for just to test fragment, see implementation -->
        <a href='#' class='text-w-grey-icons' v-for='i in 1' :key='i'>Task List</a>
        <a href='#' class='text-w-grey-icons'>TUI</a>
        <a href='#' class='text-w-dark-blue font-semibold'>TUI-122</a>
      </Breadcrumbs>
    `,
	});
