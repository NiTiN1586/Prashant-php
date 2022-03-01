<template>
	<div class="text-gray-800 w-fit inline-flex items-center">
		<label v-if="label" class="mr-4">{{ label }}</label>
		<Switch
			:model-value="modelValue"
			:class="[
				'w-[49px] h-[26px] p-1 inline-flex items-center rounded-full',
				'cursor-pointer transition-colors ease-in-out duration-200',
				'focus:outline-none focus:ring-2 focus:ring-blue-deep',
				backgroundColor,
			]"
			@update:modelValue="$emit('update:modelValue', $event)"
		>
			<span class="sr-only">{{ label }}</span>
			<span
				aria-hidden="true"
				:class="[
					'pointer-events-none',
					'h-[20px] w-[20px] rounded-full shadow-md',
					'transition ease-in-out duration-300',
					modelValue ? 'translate-x-[1.30rem]' : 'translate-x-0',
					buttonColor,
				]"
			/>
		</Switch>
	</div>
</template>

<script lang="ts">
import { defineComponent } from '@vue/runtime-core';
import { PropType } from 'vue';
import { Switch } from '@headlessui/vue';

export default defineComponent({
	components: { Switch },
	props: {
		label: {
			type: String as PropType<string | undefined>,
			default: undefined,
		},
		modelValue: {
			type: Boolean as PropType<boolean>,
			default: false,
		},
		buttonColor: {
			type: String as PropType<string>,
			default: 'bg-w-blue-deep',
		},
		backgroundColor: {
			type: String as PropType<string>,
			default: 'bg-white',
		},
	},
	emits: ['update:modelValue'],
});
</script>
