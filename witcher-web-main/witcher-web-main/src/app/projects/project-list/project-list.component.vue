<template>
	<div class="flex justify-between pb-4">
		<PageTitle title="Projects" class="pt-4" :count="result ? total : 0" />
		<div class="flex items-center">
			<Dropdown label="Export" class="py-2" is-bordered>
				<DropdownItem>
					<button class="px-3 py-2">as .PDF</button>
				</DropdownItem>
				<DropdownItem>
					<button class="px-3 py-2">as .CSV</button>
				</DropdownItem>
			</Dropdown>
			<div class="p-2">
				<Button>
					<template #icon-right>
						<AddCircleIcon class="w-7 h-7" />
					</template>
					Add project
				</Button>
			</div>
		</div>
	</div>
	<ProjectStats />
	<ProjectFilter>
		<ViewTypeButton v-model="viewType" />
	</ProjectFilter>
	<p v-if="loading">{{ t('common.loading') }}</p>
	<p v-else-if="error">{{ t('common.somethingWrong') }}</p>
	<div v-else-if="result" class="mb-40">
		<template v-if="viewType === 'list'">
			<ViewProjectList :results="result" />
			<div class="flex justify-end gap-5">
				<Select v-model="perPage" class="w-20" :options="pageOptions" />
				<Pagination
					v-model:current-page="currentPage"
					:last-page="Math.ceil(total / perPage.name)"
				/>
			</div>
		</template>
		<template v-else>
			<ViewProjectGrid :results="result" />
		</template>
	</div>
</template>

<script lang="ts" setup>
import { computed, ref } from 'vue';
import { pageOptions } from '@witcher/utils/paging';
import {
	Button,
	Dropdown,
	DropdownItem,
	PageTitle,
	Pagination,
	Select,
	ViewTypeButton,
} from '@app/components';
import { AddCircleIcon } from '@witcher/icons';
import { useProjectListQuery } from '@projects/project-list/project-list.query.generated';
import { afterCursor } from '@witcher/utils/cursor';
import { useI18n } from 'vue-i18n';
import { useAppHeader } from '@witcher/plugins/app-header';
import ProjectStats from './components/project-stats/project-stats.component.vue';
import ProjectFilter from './components/project-filter/project-filter-component.vue';
import ViewProjectList from './components/view-project-list/view-project-list.component.vue';
import ViewProjectGrid from './components/view-project-grid/view-project-grid.component.vue';

const { t } = useI18n();
const viewType = ref<'list' | 'grid'>('list');
const currentPage = ref(1);

const perPage = ref(pageOptions[0]);
const total = computed(() => result.value?.witcherProjects.totalCount ?? 0);

const { result, loading, error } = useProjectListQuery(() => ({
	perPage: perPage.value.name,
	after: afterCursor(currentPage.value - 1, perPage.value.name),
}));

const { setTitle } = useAppHeader();

setTitle(t('witcher.module-title.projects'));
</script>
