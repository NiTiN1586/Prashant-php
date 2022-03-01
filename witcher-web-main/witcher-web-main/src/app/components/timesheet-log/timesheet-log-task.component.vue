<template>
	<Disclosure v-slot="{ open }" as="div" :default-open="defaultOpen">
		<div
			class="bg-w-grey-background-light w-full flex items-stretch gap-1 p-1"
			:class="{
				'rounded-b': (isLast && !open) || (isLast && totalActivities === 0),
				'rounded-t': isFirst,
			}"
		>
			<DisclosureButton
				class="flex items-center flex-grow py-1.5 pr-4 truncate rounded-lg"
			>
				<div class="ml-5 mr-4 flex-shrink-0">
					<DropdownIcon
						class="w-2.5 h-2.5"
						:class="{
							'text-w-grey-icons -rotate-90': !open || totalActivities === 0,
							'text-w-blue-deep rotate-0': open && totalActivities !== 0,
						}"
					/>
				</div>
				<div
					class="flex flex-col gap-1 sm:flex-row sm:gap-0 sm:items-center truncate text-left sm:mr-3"
				>
					<span class="truncate">
						<span
							class="bg-w-blue-deep rounded-lg text-white font-semibold px-1 mr-2 min-w-[25px] text-center inline-block"
							v-text="totalActivities"
						/>
						<span>{{ slug }} . {{ summary }}</span>
					</span>
					<hr
						:class="[
							'hidden sm:block mx-4 h-[20px]',
							'border-l border-t-0 border-w-light-grey',
						]"
					/>
					<span class="inline-flex items-center gap-1 flex-shrink-0">
						<Avatar :src="userAvatar" size="w-6 h-6" alt="" />
						<span class="truncate">{{ userName }}</span>
					</span>
				</div>
			</DisclosureButton>
			<button class="ml-auto flex-shrink-0 py-1.5 px-2 rounded-lg">
				<MoreVertIcon class="w-9 h-9 text-w-light-grey" />
			</button>
		</div>
		<transition
			enter-active-class="transition duration-100 ease-out"
			enter-from-class="transform scale-95 opacity-0"
			enter-to-class="transform scale-100 opacity-100"
			leave-active-class="transition duration-75 ease-out"
			leave-from-class="transform scale-100 opacity-100"
			leave-to-class="transform scale-95 opacity-0"
		>
			<DisclosurePanel
				v-slot="scope"
				:class="disclosurePanelClass"
				class="overflow-x-auto"
			>
				<table class="w-full whitespace-nowrap divide-y divide-w-light-grey">
					<slot v-bind="scope" />
				</table>
			</DisclosurePanel>
		</transition>
	</Disclosure>
</template>

<script lang="ts" setup>
import { Disclosure, DisclosureButton, DisclosurePanel } from '@headlessui/vue';
import { Avatar } from '@app/components';
import { DropdownIcon, MoreVertIcon } from '@witcher/icons';

defineProps({
	disclosurePanelClass: {
		type: String,
		default: undefined,
	},
	isLast: {
		type: Boolean,
		required: true,
	},
	isFirst: {
		type: Boolean,
		required: true,
	},
	summary: {
		type: String,
		required: true,
	},
	slug: {
		type: String,
		required: true,
	},
	totalActivities: {
		type: Number,
		required: true,
	},
	userName: {
		type: String,
		required: true,
	},
	userAvatar: {
		type: String,
		required: true,
	},
	defaultOpen: {
		type: Boolean,
		default: false,
	},
});
</script>
