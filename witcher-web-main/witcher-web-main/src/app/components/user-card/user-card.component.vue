<template>
	<div
		class="border-t-4 relative border-w-blue-deep shadow rounded-lg pt-8 px-4 pb-5 w-full"
		:class="disabled ? 'opacity-50' : ''"
	>
		<div class="absolute right-5 top-6">
			<div class="inline-block relative">
				<Menu as="div" class="relative inline-block text-left">
					<div>
						<MenuButton
							class="inline-flex justify-center w-full rounded-lg text-sm font-medium text-white bg-w-blue-deep focus:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-opacity-75"
						>
							<MoreVertIcon class="w-9 h-9 text-w-light-grey mx-auto" />
						</MenuButton>
					</div>

					<transition
						enter-active-class="transition duration-100 ease-out"
						enter-from-class="transform scale-95 opacity-0"
						enter-to-class="transform scale-100 opacity-100"
						leave-active-class="transition duration-75 ease-in"
						leave-from-class="transform scale-100 opacity-100"
						leave-to-class="transform scale-95 opacity-0"
					>
						<MenuItems
							class="absolute z-10 right-0 w-40 mt-2 origin-top-right bg-white divide-y divide-gray-100 rounded-lg shadow ring-1 ring-black ring-opacity-5 focus:outline-none"
						>
							<div class="px-1 py-1">
								<MenuItem v-slot="{ active }">
									<button
										:class="[
											active ? 'bg-w-blue-deep text-white' : 'text-gray-900',
											'flex rounded-md items-center w-full px-2 py-2 text-sm',
										]"
									>
										<EditIcon class="w-4 h-4"></EditIcon>
										<span class="ml-3 text-sm" :aria-label="t('common.edit')">
											{{ t('common.edit') }}
										</span>
									</button>
								</MenuItem>
								<MenuItem v-slot="{ active }">
									<button
										:class="[
											active ? 'bg-w-blue-deep text-white' : 'text-gray-900',
											'flex rounded-md items-center w-full px-2 py-2 text-sm',
										]"
									>
										<EmailIcon class="w-4 h-4" />
										<span
											class="ml-3 text-sm"
											:aria-label="t('common.sendEmail')"
										>
											{{ t('common.sendEmail') }}
										</span>
									</button>
								</MenuItem>
							</div>
						</MenuItems>
					</transition>
				</Menu>
			</div>
		</div>
		<div
			v-if="isTeamLeader"
			class="bg-w-blue-deep w-auto text-white text-xs px-3 py-1 rounded-bl-lg rounded-br-lg absolute -top-px left-2/4 -translate-x-1/2 transform"
		>
			{{ t('common.teamLeader') }}
		</div>
		<div
			class="w-8 h-16 bg-white absolute left-8 shadow top-11 py-1 rounded-full text-center text-lg"
		>
			<span>{{ date }}</span>
		</div>
		<div class="rounded flex flex-col items-center">
			<Avatar
				alt="lead image"
				:src="profileImage"
				class="mb-3"
				size="w-20 h-20"
			></Avatar>
			<span class="text-lg text-w-dark-blue">{{ name }}</span>
		</div>
		<div class="text-w-grey-icons text-sm text-center">
			{{ email }}
		</div>
		<div
			class="text-center text-sm h-9 flex items-center justify-center text-w-dark-blue"
		>
			<b>{{ totalProjects }}</b>
			<span class="pl-2" :aria-label="t('witcher.module-title.projects')">
				{{ t('witcher.module-title.projects') }}
			</span>
			<span class="px-2">|</span>
			<b>{{ totalTasks }}</b>
			<span class="pl-2" :aria-label="t('witcher.module-title.tasks')">
				{{ t('witcher.module-title.tasks') }}
			</span>
		</div>
		<div
			class="mt-4 flex items-center text-sm justify-center bg-w-grey-background-light rounded-lg h-10 text-w-blue-deep px-6"
		>
			<div class="mr-1">
				<PostIcon class="w-4 h-4" />
			</div>
			<div class="truncate">
				{{ designation }}
			</div>
		</div>
	</div>
</template>

<script lang="ts" setup>
import { MoreVertIcon } from '@witcher/icons';
import { Menu, MenuButton, MenuItems, MenuItem } from '@headlessui/vue';
import { useI18n } from 'vue-i18n';
import { Avatar } from '../../components';
import { EditIcon, EmailIcon, PostIcon } from '../../../icons';

defineProps({
	name: { type: String, required: true },
	profileImage: { type: String, required: true },
	email: { type: String, required: true },
	date: { type: String, required: true },
	totalProjects: { type: Number, required: true },
	totalTasks: { type: Number, required: true },
	designation: { type: String, required: true },
	isTeamLeader: { type: Boolean, required: true },
	disabled: { type: Boolean, required: true },
});

const { t } = useI18n();
</script>
