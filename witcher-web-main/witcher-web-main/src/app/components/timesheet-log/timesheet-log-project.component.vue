<template>
	<Disclosure
		v-slot="{ open }"
		as="div"
		class="border-w-light-grey border rounded text-sm bg-white isolate"
		:default-open="defaultOpen"
	>
		<div
			class="flex items-stretch w-full gap-1"
			:class="{
				'rounded': !open || (open && totalTasks === 0),
				'rounded-t': open && totalTasks > 0,
			}"
		>
			<DisclosureButton
				class="flex items-center flex-grow truncate rounded-l py-2 z-10"
			>
				<div class="ml-6 mr-4 flex-shrink-0">
					<DropdownIcon
						class="w-2.5 h-2.5"
						:class="{
							'text-w-grey-icons -rotate-90': !open || totalTasks === 0,
							'text-w-blue-deep rotate-0': open && totalTasks !== 0,
						}"
					/>
				</div>
				<img
					:class="[
						'hidden sm:inline-block',
						'w-12 h-12 rounded-full object-cover mr-6',
					]"
					:src="image"
					alt=""
				/>
				<div class="flex flex-col sm:flex-row sm:items-center truncate sm:mr-3">
					<div class="text-left font-semibold truncate">
						{{ name }}
					</div>
					<hr
						:class="[
							'hidden sm:block mx-4 h-[20px]',
							'border-l border-t-0 border-w-light-grey',
						]"
					/>
					<span class="inline-flex items-center gap-1">
						<CheckCircleIcon
							class="hidden sm:inline-block h-6 w-6 text-green-400"
						/>
						<span class="text-blue-900 font-semibold truncate">
							{{ totalTasks }} tasks
						</span>
					</span>
				</div>
			</DisclosureButton>
			<button class="flex-shrink-0 py-2 px-3 rounded-r">
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
			<DisclosurePanel v-slot="scope" :class="disclosurePanelClass">
				<slot v-bind="scope" />
			</DisclosurePanel>
		</transition>
	</Disclosure>
</template>

<script lang="ts" setup>
import { Disclosure, DisclosureButton, DisclosurePanel } from '@headlessui/vue';
import { DropdownIcon, CheckCircleIcon, MoreVertIcon } from '@witcher/icons';

defineProps({
	disclosurePanelClass: {
		type: String,
		default: undefined,
	},
	defaultOpen: {
		type: Boolean,
		default: false,
	},
	name: {
		type: String,
		required: true,
	},
	totalTasks: {
		type: Number,
		required: true,
	},
	image: {
		type: String,
		required: true,
	},
});
</script>
