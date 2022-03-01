<template>
	<Listbox
		:model-value="modelValue"
		@update:model-value="$emit('update:modelValue', $event)"
	>
		<ListboxButton
			ref="listboxButtonRef"
			v-bind="$attrs"
			:class="buttonBorder"
			class="relative py-2 pl-3 pr-10 text-left bg-white rounded-lg cursor-pointer focus:outline-none focus-visible:ring-2 focus-visible:ring-opacity-75 focus-visible:ring-white focus-visible:ring-offset-indigo-100 focus-visible:ring-offset-2 focus-visible:border-indigo-500 text-sm"
		>
			<span class="block truncate">{{ modelValue[nameField] }}</span>
			<span
				class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none"
			>
				<DropdownIcon class="w-3 h-3 text-w-grey-icons" aria-hidden="true" />
			</span>
		</ListboxButton>

		<Teleport to="body">
			<transition
				v-if="options.length !== 0"
				leave-active-class="transition duration-100 ease-in"
				leave-from-class="opacity-100"
				leave-to-class="opacity-0"
			>
				<ListboxOptions
					ref="listboxOptionsRef"
					:unmount="false"
					:style="{ position, left, top }"
					class="w-min py-1 mt-1 overflow-auto bg-white rounded-lg shadow max-h-60 ring-1 ring-black ring-opacity-5 focus:outline-none text-sm"
				>
					<ListboxOption
						v-for="option in options"
						v-slot="{ active, selected }"
						:key="getKey(option)"
						:value="option"
						as="template"
					>
						<li
							:class="[
								active ? 'text-gray-900 bg-gray-100' : 'text-w-grey-icons ',
								'cursor-pointer select-none relative py-2 pl-9 pr-4',
							]"
						>
							<span
								:class="[
									selected ? 'font-medium' : 'font-normal',
									'block truncate',
								]"
							>
								{{ option[nameField] }}
							</span>
							<span
								v-if="selected"
								class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-600"
							>
								<CheckIcon class="w-5 h-5" aria-hidden="true" />
							</span>
						</li>
					</ListboxOption>
				</ListboxOptions>
			</transition>
		</Teleport>
	</Listbox>
</template>

<script lang="ts">
import {
	PropType,
	ref,
	ComponentPublicInstance,
	watchPostEffect,
	defineComponent,
} from 'vue';
import {
	Listbox,
	ListboxButton,
	ListboxOption,
	ListboxOptions,
} from '@headlessui/vue';
import { CheckIcon, DropdownIcon } from '@witcher/icons';
import { computePosition } from '@floating-ui/dom';

export default defineComponent({
	components: {
		Listbox,
		ListboxButton,
		ListboxOption,
		ListboxOptions,
		CheckIcon,
		DropdownIcon,
	},
	inheritAttrs: false,
	props: {
		modelValue: {
			type: Object as PropType<Record<string, unknown>>,
			required: true,
		},
		options: {
			type: Array as PropType<ReadonlyArray<Record<string, unknown>>>,
			required: true,
		},
		idField: {
			type: String as PropType<string>,
			default: 'id',
		},
		nameField: {
			type: String as PropType<string>,
			default: 'name',
		},
		buttonBorder: {
			type: String as PropType<string>,
			default: 'border border-indigo-100',
		},
	},
	emits: ['update:modelValue'],
	setup(props) {
		const getKey = (option: Record<string, unknown>) => {
			return (option[props.idField] || option[props.nameField]) as string;
		};

		const listboxButtonRef = ref<ComponentPublicInstance>();
		const listboxOptionsRef = ref<ComponentPublicInstance>();
		const position = ref<string>();
		const top = ref<string>();
		const left = ref<string>();

		watchPostEffect(() => {
			if (!listboxButtonRef.value?.$el || !listboxOptionsRef.value?.$el) return;

			computePosition(listboxButtonRef.value.$el, listboxOptionsRef.value.$el, {
				strategy: 'absolute',
				// TODO: find how to open up
				placement: 'bottom-start',
			}).then((data) => {
				position.value = data.strategy;
				top.value = `${data.y}px`;
				left.value = `${data.x}px`;
			});
		});

		return { getKey, position, left, top, listboxButtonRef, listboxOptionsRef };
	},
});
</script>
