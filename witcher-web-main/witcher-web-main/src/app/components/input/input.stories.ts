import { Meta, Story } from '@storybook/vue3';
import { defineComponent } from '@vue/runtime-core';
import {
	CalendarIcon,
	InvoicesIcon,
	SearchIcon,
	TimesheetIcon,
} from '@witcher/icons';
import { ref } from 'vue';
import Component from './input.component.vue';

export default {
	title: 'Components / Input',
	component: Component,
} as Meta;

export const Input: Story = () => {
	return defineComponent({
		components: {
			Input: Component,
			InvoicesIcon,
			SearchIcon,
			CalendarIcon,
			TimesheetIcon,
		},
		setup() {
			const date = ref('');
			const time = ref('');
			const search = ref('');

			return { date, time, search };
		},
		template: `
      <div class='flex flex-wrap gap-5'>
      <Input type='date' name='date' v-model='date' input-class='shadow'>
        <template #icon-left>
          <CalendarIcon class='w-6 h-6' />
        </template>
      </Input>
      <Input type='time' name='time' v-model='time'>
        <template #icon-right>
          <TimesheetIcon class='w-6 h-6' />
        </template>
      </Input>
      <Input type='email' name='email' placeholder='you@example.com' v-model='search'>
        <template #icon-left>
          <SearchIcon class='w-5 h-5' />
        </template>
        <template #icon-right>
          <InvoicesIcon class='w-6 h-6' />
        </template>
      </Input>
      </div>
    `,
	});
};
