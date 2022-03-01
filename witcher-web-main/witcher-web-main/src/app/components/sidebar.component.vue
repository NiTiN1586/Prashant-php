<template>
	<PrivateLogo v-if="isMenuOpen" class="mb-10 mx-auto" />
	<PrivateLogoIcon v-else class="mb-10 mx-auto w-9 h-9 text-w-blue-deep" />
	<ul>
		<li v-for="(group, groupIndex) in menu" :key="groupIndex" class="mb-10">
			<div
				v-if="group.label && isMenuOpen"
				class="mb-5 font-medium text-sm text-w-grey-icons"
			>
				{{ group.label }}
			</div>
			<ul>
				<RouterLink
					v-for="(item, itemIndex) in group.items"
					v-slot="{ href, navigate, isExactActive }"
					:key="itemIndex"
					custom
					:to="item.to"
				>
					<li
						:title="item.label"
						class="mb-3 text-sm"
						:style="{ '--tw-shadow': '0px 10px 20px rgba(43, 94, 224, 0.2)' }"
						:class="{
							'bg-gradient-to-r from-w-gradient-button-1 to-w-gradient-button-2 shadow':
								isExactActive && !item.disabled,
							'text-gray-400 pointer-events-none': item.disabled,
							'rounded': isMenuOpen,
						}"
						@click="hideMenu"
					>
						<a
							:class="{
								'text-white': isExactActive && !item.disabled,
							}"
							class="py-3 px-5 block flex gap-4 items-center"
							:href="href"
							@click="navigate"
						>
							<component :is="item.icon" class="w-6 h-6" />
							<div v-if="isMenuOpen">
								<span>{{ item.label }}</span>
								<span
									v-if="item.badge"
									class="bg-red-500 text-white rounded-full px-2"
								>
									{{ item.badge }}
								</span>
							</div>
						</a>
					</li>
				</RouterLink>
			</ul>
		</li>
	</ul>
</template>

<script lang="ts">
import { Component, defineComponent } from '@vue/runtime-core';
import { RouterLink } from 'vue-router';
import {
	PrivateLogoIcon,
	ProjectsIcon,
	TasksIcon,
	TimesheetIcon,
	DashboardIcon,
} from '@witcher/icons';
import { useSettings } from '../../plugins/settings';

import PrivateLogo from './private-logo.component.vue';

export default defineComponent({
	components: { RouterLink, PrivateLogo, PrivateLogoIcon },
	setup() {
		const { hideMenu, isMenuOpen } = useSettings();
		return { menu, hideMenu, isMenuOpen };
	},
});

type MenuItem = {
	to: string;
	label: string;
	badge?: number;
	icon: Component;
	disabled?: boolean;
};

type MenuGroup = {
	label?: string;
	items: MenuItem[];
};

const menu: MenuGroup[] = [
	{
		items: [{ to: '/', label: 'Dashboard', icon: DashboardIcon }],
	},
	{
		label: 'Project Manager',
		items: [
			{ to: '/tasks', label: 'Tasks', icon: TasksIcon },
			{ to: '/projects', label: 'Projects', icon: ProjectsIcon },
			{ to: '/timesheet', label: 'Timesheet', icon: TimesheetIcon },
		],
	},
];
</script>
