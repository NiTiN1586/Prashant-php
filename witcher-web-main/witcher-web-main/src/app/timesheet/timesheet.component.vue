<template>
	<Header />
	<Overall />
	<TimesheetProjectList />
</template>

<script lang="ts">
import { defineComponent } from '@vue/runtime-core';
import { useI18n } from 'vue-i18n';
import { useAppHeader } from '@witcher/plugins/app-header';
import Header from './components/timesheet-header.component.vue';
import Overall from './components/timesheet-overall/timesheet-overall.component.vue';
import TimesheetProjectList from './components/timesheet-list/timesheet-project-list.component.vue';

export default defineComponent({
	components: { Header, Overall, TimesheetProjectList },
	setup() {
		const { t } = useI18n();
		const { setTitle } = useAppHeader();

		setTitle(t('witcher.module-title.timesheet'));
	},
});
</script>
