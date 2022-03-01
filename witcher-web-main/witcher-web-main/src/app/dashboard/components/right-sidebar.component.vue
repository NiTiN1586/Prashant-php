<template>
	<section class="relative">
		<div class="border rounded border-[#C4D6F7] p-6">
			<Row>
				<Column class="w-full">
					<div class="text-w-dark-blue font-medium text-lg">
						{{ t('dashboard.sidebar.header') }}
					</div>
					<Row class="mt-6 pb-8">
						<Column class="w-3/5 text-center">
							<div class="inline-grid items-center justify-items-center">
								<SvgCircle
									:stroke-width="14"
									:diameter="210"
									:total="100"
									:current="efficiencyRate.factoryProject.percentage"
									class="row-span-full col-span-full"
								/>
								<SvgCircle
									:stroke-width="14"
									:diameter="160"
									:total="100"
									:current="efficiencyRate.scalingProject.percentage"
									class="row-span-full col-span-full"
									foreground-color="text-[#0DE4E4]"
								/>
								<div
									class="row-span-full col-span-full text-xl text-[26px] font-semibold text-w-dark-blue"
								>
									{{
										`${
											efficiencyRate.factoryProject.hours +
											efficiencyRate.scalingProject.hours
										}${t('dashboard.sidebar.hour.shortForm')}`
									}}
									<h3 class="font-normal text-sm">
										{{ t('dashboard.sidebar.worked.text') }}
									</h3>
								</div>
							</div>
						</Column>
						<Column class="w-2/5 mt-6">
							<div class="list-item text-2xl text-[#0DE4E4] inline">
								<div>
									<h6 class="text-sm text-w-dark-blue font-normal">
										{{ efficiencyRate.factoryProject.name }}
									</h6>
									<h1 class="text-[26px] font-semibold">
										{{ efficiencyRate.factoryProject.percentage }}%
										<span class="text-[16px] text-w-dark-blue font-normal">{{
											`${efficiencyRate.factoryProject.hours}${t(
												'dashboard.sidebar.hour.shortForm',
											)}`
										}}</span>
									</h1>
								</div>
							</div>
							<div class="list-item text-2xl text-w-blue-deep inline mt-6">
								<h6 class="text-sm text-w-dark-blue font-normal">
									{{ efficiencyRate.scalingProject.name }}
								</h6>
								<h1 class="text-[26px] font-semibold">
									{{ efficiencyRate.scalingProject.percentage }}%
									<span class="text-[16px] text-w-dark-blue font-normal">{{
										`${efficiencyRate.scalingProject.hours}${t(
											'dashboard.sidebar.hour.shortForm',
										)}`
									}}</span>
								</h1>
							</div>
						</Column>
					</Row>
				</Column>
			</Row>
			<Row class="border-t border-b border-[#C4D6F7]">
				<Column class="w-full mt-4 py-4">
					<SelectComponent
						v-model="selectedCAO"
						button-border="border-none"
						class="w-30 -mt-1 float-right"
						:options="consumingActivityOptions"
					/>
					<ProgressWidget
						:title="t('dashboard.sidebar.consuming.activities')"
						:items="consumingActivityBar"
					/>
				</Column>
			</Row>
			<Row>
				<Column class="w-full mt-4 py-4">
					<SelectComponent
						v-model="selectedPAO"
						button-border="border-none"
						class="w-30 -mt-1 float-right"
						:options="projectActivityOptions"
					/>
					<ProgressWidget
						:title="t('dashboard.sidebar.project.activity')"
						:items="projectActivityBar"
					/>
				</Column>
			</Row>
		</div>
		<div></div>
	</section>
</template>

<script lang="ts">
import { defineComponent } from '@vue/runtime-core';
import { useI18n } from 'vue-i18n';
import { ref } from 'vue';
import Row from '../../../components/grid/row.component.vue';
import Column from '../../../components/grid/column.component.vue';
import ProgressWidget from '../../components/progress-widget/progress-widget.component.vue';
import SvgCircle from '../../components/svg-circle/svg-circle.component.vue';
import SelectComponent from '../../components/select/select.component.vue';
import { getPicsumImage } from '../../../utils/get-picsum-image';

export default defineComponent({
	components: {
		Row,
		Column,
		ProgressWidget,
		SvgCircle,
		SelectComponent,
	},
	setup() {
		const { t } = useI18n();

		const efficiencyRate = {
			factoryProject: {
				name: 'Factory Projects',
				percentage: 10,
				hours: 50,
			},
			scalingProject: {
				name: 'Scaling Projects',
				percentage: 30,
				hours: 100,
			},
		};

		const consumingActivityBar = [
			{ title: 'Development', value: '13:05 Hrs', progress: 10 },
			{ title: 'Meeting', value: '15:05 Hrs', progress: 50 },
			{ title: 'UI/UX Design', value: '23:05 Hrs', progress: 30 },
		];
		const projectActivityBar = [
			{
				title: 'Witcher CRM',
				value: '32:05 Hrs',
				progress: 50,
				avatar: getPicsumImage(1),
			},
			{
				title: 'Witcher CRM',
				value: '32:05 Hrs',
				progress: 50,
				avatar: getPicsumImage(1),
			},
			{
				title: 'Witcher CRM',
				value: '32:05 Hrs',
				progress: 50,
				avatar: getPicsumImage(1),
			},
		];

		const consumingActivityOptions = [{ name: '1' }, { name: '2' }];

		const projectActivityOptions = [
			{ name: t('dashboard.sidebar.most.active') },
		];

		const selectedCAO = ref(consumingActivityOptions[0]);
		const selectedPAO = ref(projectActivityOptions[0]);

		return {
			t,
			efficiencyRate,
			consumingActivityBar,
			projectActivityBar,
			consumingActivityOptions,
			projectActivityOptions,
			selectedCAO,
			selectedPAO,
		};
	},
});
</script>
