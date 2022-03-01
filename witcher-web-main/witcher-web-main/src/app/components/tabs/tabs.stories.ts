import { Meta, Story } from '@storybook/vue3';
import { defineComponent } from '@vue/runtime-core';
import {
	TabGroup as HeadlessTabGroup,
	TabList as HeadlessTabList,
	Tab as HeadlessTab,
	TabPanels as HeadlessTabPanels,
	TabPanel as HeadlessTabPanel,
} from '@headlessui/vue';
import Tab from './tab.component.vue';
import TabList from './tab-list.component.vue';

export default {
	title: 'Components / Tabs',
} as Meta;

export const Tabs: Story = () => {
	return defineComponent({
		components: {
			HeadlessTabGroup,
			HeadlessTabList,
			HeadlessTab,
			HeadlessTabPanels,
			HeadlessTabPanel,
			TabList,
			Tab,
		},
		setup() {
			const tabs = [
				{ id: 1, name: 'Overview', total: 0, important: false },
				{ id: 2, name: 'Technologies', total: 10, important: false },
				{ id: 3, name: 'Team', total: 10, important: false },
				{
					id: 4,
					name: 'Project Details',
					total: 0,
					important: false,
				},
				{ id: 5, name: 'TimeLine & Costs', total: 0, important: true },
			];

			return { tabs };
		},
		template: `
      <div>
      <HeadlessTabGroup as='div'>
        <HeadlessTabList as='template'>
          <TabList>
            <HeadlessTab
              v-for='tab in tabs'
              v-slot='{ selected }'
              :key='tab.id'
              as='template'
            >
              <Tab
                :name='tab.name'
                :selected='selected'
                :important='tab.important'
                :count='tab.total'
              />
            </HeadlessTab>
          </TabList>
        </HeadlessTabList>
        <HeadlessTabPanels>
          <HeadlessTabPanel v-for='tab in tabs' :key='tab.id'>
            <h1>{{ tab.name }}</h1>
          </HeadlessTabPanel>
        </HeadlessTabPanels>
      </HeadlessTabGroup>
      </div>
    `,
	});
};
