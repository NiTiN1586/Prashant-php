<template>
	<div class="relative">
		<div class="flex flex-wrap justify-between pb-4">
			<PageTitle title="Timesheet logs" class="pt-4" :count="150" />
			<div class="flex flex-wrap items-center">
				<div class="bg-w-light-orange/10 p-2 mr-2 rounded-lg flex">
					<p class="text-w-orange text-sm flex items-center">
						<span class="rounded mr-2 w-1 h-full bg-w-orange" />
						<span class="flex flex-wrap">
							An Invoice will be
							<b class="font-semibold mx-1">auto-generated</b> on:
							<b class="font-semibold mx-1">5 May 2021</b>
						</span>
					</p>
				</div>
				<Dropdown label="Export" class="py-2 mr-2" is-bordered>
					<DropdownItem>
						<button class="px-3 py-2">as .PDF</button>
					</DropdownItem>
					<DropdownItem>
						<button class="px-3 py-2">as .CSV</button>
					</DropdownItem>
				</Dropdown>
				<Button
					:color="isModalOpen ? 'danger' : 'blue'"
					@click="isModalOpen = !isModalOpen"
				>
					<template #icon-right>
						<CloseIcon v-if="isModalOpen" class="w-7 h-7" />
						<AddCircleIcon v-else class="w-7 h-7" />
					</template>
					{{ isModalOpen ? 'Cancel' : 'Add project' }}
				</Button>
			</div>
		</div>
		<div
			v-show="isModalOpen"
			:class="[
				'fixed left-0 w-full h-screen z-10',
				{ 'pl-[210px]': isMenuOpen },
			]"
		>
			<Container
				variant="2xl:container"
				padding="p-0"
				class="bg-black/5 w-full h-full"
			>
				<TimesheetCreate />
			</Container>
		</div>
	</div>
</template>

<script lang="ts" setup>
import { ref } from 'vue';
import { Button, DropdownItem, Dropdown, PageTitle } from '@app/components';
import { Container } from '@witcher/components';
import { AddCircleIcon, CloseIcon } from '@witcher/icons';
import { useSettings } from '@witcher/plugins/settings';
import TimesheetCreate from './timesheet-create.component.vue';

const { isMenuOpen } = useSettings();
const isModalOpen = ref(false);
</script>
