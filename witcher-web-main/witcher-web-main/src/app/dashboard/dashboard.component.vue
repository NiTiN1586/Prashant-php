<template>
	<section class="relative">
		<Row>
			<Column class="w-full lg:w-4/6">
				<TopHeader />
				<WeeklyTask />
			</Column>
			<Column class="hidden lg:block lg:w-2/6">
				<Sidebar />
			</Column>
		</Row>
	</section>
</template>

<script lang="ts">
import { defineComponent } from '@vue/runtime-core';
import { useI18n } from 'vue-i18n';
import { useAppHeader } from '@witcher/plugins/app-header';
import Row from '../../components/grid/row.component.vue';
import Column from '../../components/grid/column.component.vue';
import TopHeader from './components/header.component.vue';
import Sidebar from './components/right-sidebar.component.vue';
import WeeklyTask from './components/weekly-task.component.vue';

export default defineComponent({
	components: {
		Row,
		Column,
		TopHeader,
		Sidebar,
		WeeklyTask,
	},
	setup() {
		const { t } = useI18n();
		const { setTitle } = useAppHeader();

		setTitle(t('witcher.module-title.dashboard'));
	},
});
</script>
