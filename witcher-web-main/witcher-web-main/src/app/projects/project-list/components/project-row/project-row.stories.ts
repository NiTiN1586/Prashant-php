import { Meta, Story } from '@storybook/vue3';
import { defineComponent } from '@vue/runtime-core';
import { Container } from '@witcher/components';
import { getPicsumImage } from '@witcher/utils/get-picsum-image';
import Component from './project-row-item.component.vue';
import Header from './project-row-header.component.vue';

export default {
	title: 'Components / Project Row',
	component: Component,
	parameters: { layout: 'none' },
} as Meta;

export const ProjectRow: Story = () => {
	return defineComponent({
		components: { ProjectRow: Component, Header, Container },
		setup: () => ({ getPicsumImage }),
		template: `
      <Container fluid class='overflow-x-auto'>
      <table class='min-w-full table-fixed' style='border-spacing: 0 10px; border-collapse: separate'>
        <thead>
        <Header />
        </thead>
        <tbody>
        <ProjectRow v-for='i in 10' :key='i' :project="{
          name: 'Jagaad Witcher',
          slug: 'project-' + i,
          type: 'Factory',
          efficiency: 50 + i,
          progress: 60 + i,
          points: 2000 + i,
          phase: 'Phase ' + i,
          image: getPicsumImage(i + 1, 100, 100),
          manager: {
            name: 'Jo Askin',
            image: getPicsumImage(i + 2, 100, 100),
          },
          leader: {
            name: 'Jo Askin',
            image: getPicsumImage(i + 3, 100, 100),
          },
          client: {
            name: 'Jo Askin',
            image: getPicsumImage(i + 4, 100, 100),
          },
        }" />
        </tbody>
      </table>
      </Container>
    `,
	});
};
