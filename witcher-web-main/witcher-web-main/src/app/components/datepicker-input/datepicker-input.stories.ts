import { Meta, Story } from '@storybook/vue3';
import { defineComponent } from '@vue/runtime-core';
import Component from './datepicker-input.component.vue';

export default {
	title: 'Components / Datepicker Input',
	component: Component,
} as Meta;

const html = String.raw;

export const DatepickerInput: Story = () => {
	return defineComponent({
		components: { DatepickerInput: Component },
		template: html`
			<div class="flex text-w-dark-blue font-medium">
				<div class="m-4">
					<label class="ml-1">Focus on Input</label>
					<DatepickerInput :show-icon="false" input-class="h-8" />
				</div>
				<div class="m-4">
					<label class="ml-1">Click on Icon</label>
					<DatepickerInput input-class="h-8" />
				</div>
				<div class="m-4">
					<label class="ml-1">Hover</label>
					<DatepickerInput
						input-class="h-8"
						:show-icon="false"
						visibility="hover"
					/>
				</div>
			</div>
		`,
	});
};
