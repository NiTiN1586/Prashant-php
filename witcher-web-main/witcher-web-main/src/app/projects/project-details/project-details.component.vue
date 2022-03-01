<template>
	<div v-if="loading">Loading...</div>
	<div v-else-if="error">Something went wrong...</div>
	<div v-else-if="project">
		<div class="flex flex-col sm:flex-row items-center sm:items-stretch gap-6">
			<img
				:src="getPicsumImage(0, 72, 72)"
				alt=""
				class="w-[72px] h-[72px] object-cover"
			/>
			<div class="flex flex-col justify-between overflow-hidden">
				<div class="flex gap-3.5 items-center">
					<h2 class="text-2xl truncate">{{ project.name }}</h2>
					<span
						v-if="projectType"
						class="text-sm bg-w-grey-background-light py-1.5 px-3 rounded-lg flex items-center gap-2 flex-shrink-0"
					>
						<span class="inline-block w-3 h-3 bg-w-blue-deep rounded-full" />
						<span>{{ projectType }}</span>
					</span>
				</div>
				<p v-if="project.description" class="sm:truncate text-sm text-black">
					{{ project.description }}
				</p>
			</div>
			<div class="ml-auto">
				<div class="flex items-center mb-3">
					<span class="mr-3 text-sm">Team leader:</span>
					<Avatar
						:src="getPicsumImage(2022)"
						:alt="`User ${project.createdBy.userId} image`"
						:title="`User image with id: ${project.createdBy.userId}`"
					/>
				</div>
				<Dropdown label="Quick links">
					<DropdownItem v-if="project.externalTrackerLink">
						<a
							:href="project.externalTrackerLink"
							target="_blank"
							class="px-3 py-2 flex items-center gap-2"
						>
							<ExternalLinkIcon class="w-4 h-4 inline-block" />
							Jira
						</a>
					</DropdownItem>
					<DropdownItem v-if="project.confluenceLink">
						<a
							:href="project.confluenceLink"
							target="_blank"
							class="px-3 py-2 flex items-center gap-2"
						>
							<ExternalLinkIcon class="w-4 h-4 inline-block" />
							Confluence
						</a>
					</DropdownItem>
				</Dropdown>
			</div>
		</div>

		<TabList class="mt-12 mb-5">
			<RouterLink
				v-for="tab in tabs"
				:key="tab.name"
				v-slot="{ navigate, href, isExactActive }"
				custom
				:to="{ name: tab.name }"
			>
				<a :href="href" @click="navigate">
					<Tab
						:name="tab.title"
						:selected="isExactActive"
						:important="tab.important"
						:count="tab.count"
					/>
				</a>
			</RouterLink>
		</TabList>

		<div>
			<RouterView />
		</div>
	</div>
</template>

<script lang="ts" setup>
import { computed } from '@vue/runtime-core';
import { RouterLink, RouterView, useRoute } from 'vue-router';
import { useI18n } from 'vue-i18n';
import { useResult } from '@vue/apollo-composable';
import {
	Avatar,
	Tab,
	TabList,
	Dropdown,
	DropdownItem,
} from '@witcher/app/components';
import { ExternalLinkIcon } from '@witcher/icons';
import { getPicsumImage } from '../../../utils/get-picsum-image';
import { useProjectDetailsQuery } from './project-details.query.generated';

type TabType = {
	name: string;
	title: string;
	count?: number;
	important?: boolean;
};

const { t } = useI18n();
const route = useRoute();
const id = route.params.id as string;
const { loading, error, result } = useProjectDetailsQuery({ id });
const project = useResult(result);
const projectType = useResult(
	result,
	undefined,
	(data) => data.witcherProject.projectType.businessBranch.friendlyName,
);

const tabs = computed<TabType[]>(() => [
	{
		name: 'project-details',
		title: t('projects.project-details.overview'),
	},
	{
		name: 'project-tasks',
		title: t('projects.project-details.tasks'),
		count: result.value?.witcherProject.tasks.totalCount ?? 0,
	},
	{
		name: 'project-technologies',
		title: t('projects.project-details.technologies'),
		count: 10,
	},
	{ name: 'project-team', title: t('projects.project-details.team') },
	{ name: 'project-edit', title: t('projects.project-details.edit') },
	{
		name: 'project-timeline',
		title: t('projects.project-details.timeline'),
		important: true,
	},
]);
</script>
