<template>
	<section class="relative">
		<Row class="items-center">
			<Column class="w-10/12">
				<div
					v-for="(card, index) in infoCards"
					:key="index"
					:class="{ 'ml-4': index !== 0 }"
					class="bg-w-grey-background-light py-2 rounded px-3 inline-block text-w-dark-blue"
				>
					<component
						:is="card.icon"
						:class="[
							index === 0 ? 'h-[17px] mt-[-3px]' : 'h-[24px] align-text-top',
						]"
						class="inline mr-2"
					/>
					<span
						class="text-w-blue-deep font-medium text-[18px] mr-1 align-middle"
						v-text="card.stats"
					></span>
					<span class="font-normal text-sm" v-text="card.text"></span>
				</div>
			</Column>
			<Column class="w-1/6">
				<Select
					v-model="sortBy"
					button-border="border border-[#C4D6F7]"
					class="w-30 float-right"
					:options="sortByOptions"
				/>
			</Column>
		</Row>
		<SkillChart />
	</section>
</template>

<script lang="ts">
import { defineComponent } from '@vue/runtime-core';
import { useI18n } from 'vue-i18n';
import { ref } from 'vue';
import Row from '../../../../components/grid/row.component.vue';
import Column from '../../../../components/grid/column.component.vue';
import { CodeIcon, TimesheetIcon } from '../../../../icons';
import Select from '../../../components/select/select.component.vue';
import SkillChart from './components/skill-chart.component.vue';

export default defineComponent({
	components: {
		Row,
		Column,
		Select,
		SkillChart,
	},
	setup() {
		const { t } = useI18n();
		const infoCards = [
			{
				icon: TimesheetIcon,
				stats: '765h',
				text: 'Time spent',
			},
			{
				icon: CodeIcon,
				stats: '10',
				text: 'Technologies',
			},
		];

		const sortByOptions = [{ name: 'sort by time' }];

		const sortBy = ref(sortByOptions[0]);

		return { t, infoCards, sortByOptions, sortBy };
	},
});
</script>
