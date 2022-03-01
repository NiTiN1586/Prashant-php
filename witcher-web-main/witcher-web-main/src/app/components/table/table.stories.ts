import { Meta, Story } from '@storybook/vue3';
import { defineComponent } from '@vue/runtime-core';
import TableRow from './table-row.component.vue';
import TableData from './table-data.component.vue';

export default {
	title: 'Components / Table',
	subcomponents: { TableRow, TableData },
	parameters: {
		layout: 'none',
	},
} as Meta;

export const Table: Story = () => {
	return defineComponent({
		components: { TableRow, TableData },
		template: `
      <div class='overflow-x-auto p-5'>
      <table
        class='w-full border-separate [border-spacing:0_10px] md:[border-spacing:0_20px]'>
        <thead>
        <tr class='whitespace-nowrap'>
          <th class='text-left px-4'>Header 1</th>
          <th class='text-left px-4'>Header 2</th>
          <th class='text-left px-4'>Header 3</th>
          <th class='text-left px-4'>Header 4</th>
          <th class='text-left px-4'>Header 5</th>
        </tr>
        </thead>
        <tbody>
        <TableRow v-for='i in 7' :key='i' class='cursor-pointer'>
          <TableData class='p-4 group-hover:bg-yellow-100' is-first>Lorem</TableData>
          <TableData class='p-4 group-hover:bg-yellow-100'>ipsum</TableData>
          <TableData class='p-4 group-hover:bg-yellow-100'>dolor</TableData>
          <TableData class='p-4 group-hover:bg-yellow-100'>
            <div class='w-[200px] truncate'>
              Lorem ipsum dolor sit amet, consectetur adipisicing elit. Deserunt, illum.
            </div>
          </TableData>
          <TableData class='truncate p-4 group-hover:bg-yellow-100' is-last>
            amet!
          </TableData>
        </TableRow>
        </tbody>
        <tfoot>
        <tr class='whitespace-nowrap'>
          <th class='text-left px-4'>Footer 1</th>
          <th class='text-left px-4'>Footer 2</th>
          <th class='text-left px-4'>Footer 3</th>
          <th class='text-left px-4'>Footer 4</th>
          <th class='text-left px-4'>Footer 5</th>
        </tr>
        </tfoot>
      </table>
      </div>
    `,
	});
};
