import { Meta, Story } from '@storybook/vue3';
import { defineComponent } from '@vue/runtime-core';
import DropdownItem from './dropdown-item.component.vue';
import Component from './dropdown.component.vue';

export default {
	title: 'Components / Dropdown',
	component: Component,
} as Meta;

const html = String.raw;

export const Dropdown: Story = () => {
	return defineComponent({
		components: { Dropdown: Component, DropdownItem },
		template: html`
			<div class="flex gap-5">
				<Dropdown label="Export">
					<DropdownItem>
						<button class="px-3 py-2">Download as PDF</button>
					</DropdownItem>
					<DropdownItem>
						<button class="px-3 py-2">Download as CSV</button>
					</DropdownItem>
				</Dropdown>

				<Dropdown label="Quick access links" isBordered>
					<DropdownItem>
						<a
							href="https://jagaad.com"
							target="_blank"
							class="inline-block px-3 py-2"
						>
							Download as PDF
						</a>
					</DropdownItem>
					<DropdownItem>
						<a
							href="https://jagaad.com"
							target="_blank"
							class="inline-block px-3 py-2"
						>
							Download as CSV
						</a>
					</DropdownItem>
				</Dropdown>
			</div>
		`,
	});
};
