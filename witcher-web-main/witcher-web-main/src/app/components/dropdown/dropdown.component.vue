<template>
	<Menu as="div" class="relative inline-block text-left">
		<MenuButton
			:class="{ 'border border-indigo-100': isBordered }"
			class="relative pl-3 py-2 text-sm font-medium rounded-lg focus:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-opacity-75"
		>
			<span class="block truncate pr-10">{{ label }}</span>
			<span
				class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none"
			>
				<svg
					viewBox="0 0 10 10"
					fill="none"
					xmlns="http://www.w3.org/2000/svg"
					class="w-3 h-3 text-gray-400"
					aria-hidden="true"
				>
					<path
						d="M1 3L5 7L9 3"
						stroke="#92AAD7"
						stroke-width="1.5"
						stroke-linecap="round"
						stroke-linejoin="round"
					/>
					<path
						d="M1 3L5 7L9 3"
						stroke="#889DC6"
						stroke-width="1.5"
						stroke-linecap="round"
						stroke-linejoin="round"
					/>
				</svg>
			</span>
		</MenuButton>

		<transition
			enter-active-class="transition duration-100 ease-out"
			enter-from-class="transform scale-95 opacity-0"
			enter-to-class="transform scale-100 opacity-100"
			leave-active-class="transition duration-75 ease-in"
			leave-from-class="transform scale-100 opacity-100"
			leave-to-class="transform scale-95 opacity-0"
		>
			<MenuItems
				as="ul"
				class="py-1 mt-2 absolute left-0 origin-top-right bg-white rounded-lg shadow ring-1 ring-black ring-opacity-5 focus:outline-none whitespace-nowrap"
			>
				<slot />
			</MenuItems>
		</transition>
	</Menu>
</template>

<script lang="ts" setup>
import { PropType } from 'vue';
import { Menu, MenuButton, MenuItems } from '@headlessui/vue';

defineProps({
	label: {
		type: String as PropType<string>,
		required: true,
	},
	isBordered: {
		type: Boolean as PropType<boolean>,
		default: false,
	},
});
</script>
