import { Meta, Story } from '@storybook/vue3';
import { defineComponent } from '@vue/runtime-core';
import { ref } from 'vue';
import { Container } from '../../../components';
import Button from '../button/button.component.vue';
import Component from './dialog.component.vue';

export default {
	title: 'Components / Dialog',
	component: Component,
} as Meta;

const html = String.raw;

export const Dialog: Story = () => {
	return defineComponent({
		components: { DialogModal: Component, Container, Btn: Button },
		setup: () => {
			const isOpen = ref(false);

			const showDialog = () => {
				isOpen.value = true;
			};

			const hideDialog = () => {
				isOpen.value = false;
			};

			return {
				showDialog,
				hideDialog,
				isOpen,
			};
		},
		template: html`
			<Container>
				<Btn @click="showDialog">Show Dialog</Btn>
				<DialogModal v-model="isOpen" title="Dialog with action buttons">
					<template #body>
						Porro nulla id vero perspiciatis nulla nihil. Facilis eum iusto
						ullam saepe inventore sapiente. Eum voluptatum autem vel non.
						Similique reprehenderit molestiae quo quasi laboriosam. Est
						voluptatem consequatur. A omnis eos accusamus accusamus reiciendis
						rem quibusdam et. Occaecati dolor soluta sed quisquam rerum.
					</template>
					<template #actions>
						<button @click="hideDialog" class="text-icon-grey mr-3">
							Cancel
						</button>
						<Btn @click="hideDialog">Save</Btn>
					</template>
				</DialogModal>
			</Container>
		`,
	});
};
