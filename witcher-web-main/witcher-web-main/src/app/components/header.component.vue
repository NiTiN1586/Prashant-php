<template>
	<header class="sticky top-0 z-20 bg-white">
		<Container variant="2xl:container" padding="px-6 2xl:px-16">
			<div class="border-b border-w-light-grey">
				<Row class="py-3">
					<Column class="md:w-full lg:w-1/2 inline-flex">
						<button class="mr-4" @click.stop="toggleMenu">
							<HamburgerIcon class="h-[32px] w-[32px]" />
						</button>
						<span class="mr-16 my-auto" v-text="title"></span>
						<SearchIcon class="h-[20px] w-[20px] text-w-grey-icons my-auto" />

						<input
							type="text"
							placeholder="Search on witcher..."
							class="text-sm border-none focus:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-opacity-75"
						/>
					</Column>
					<Column
						class="md:w-full lg:w-1/2 inline-flex justify-end items-center"
					>
						<Select
							v-if="showBranches"
							v-model="selectedBranch"
							:options="branches"
							button-border="border-none"
						/>
						<Select
							v-if="showDate"
							v-model="selectedDay"
							:options="days"
							button-border="border-none"
						/>
						<AddCircleIcon class="h-[36px] w-[36px] text-w-blue-deep ml-4" />
						<BellIcon class="h-[24px] w-[24px] text-w-dark-blue ml-8" />
						<Avatar
							:src="userInfo.avatar"
							alt="Profile picture"
							size="h-[42px] w-[42px]"
							rounded="rounded-[10px]"
							class="ml-8"
						/>
					</Column>
				</Row>
			</div>
		</Container>
	</header>
</template>

<script lang="ts" setup>
import { useSettings } from '@witcher/plugins/settings';
import { computed, ref } from 'vue';
import { Container, Row, Column } from '@witcher/components';
import { Avatar } from '@app/components';
import {
	HamburgerIcon,
	SearchIcon,
	AddCircleIcon,
	BellIcon,
} from '@witcher/icons/';
import { useAppHeader } from '@witcher/plugins/app-header';
import { getPicsumImage } from '@witcher/utils/get-picsum-image';
import { useRoute } from 'vue-router';
import Select from './select/select.component.vue';

const route = useRoute();
const { toggleMenu } = useSettings();
const userInfo = {
	avatar: getPicsumImage(),
};

const { title } = useAppHeader();

const branches = [
	{ name: 'All bussiness branchs' },
	{ name: 'branch 1' },
	{ name: 'branch 2' },
	{ name: 'branch 3' },
];
const selectedBranch = ref(branches[0]);

const days = [
	{ name: 'Today' },
	{ name: 'Yesterday' },
	{ name: 'Day 1' },
	{ name: 'Day 2' },
];
const selectedDay = ref(days[0]);

const routes = ['task-details', 'project-details'];
const showBranches = computed(() => !routes.includes(route.name as string));
const showDate = computed(() => !routes.includes(route.name as string));
</script>
