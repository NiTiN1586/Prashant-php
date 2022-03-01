<template>
	<RadioGroup
		class="inline-flex"
		:model-value="modelValue"
		@update:modelValue="$emit('update:modelValue', $event)"
	>
		<RadioGroupOption v-slot="{ checked }" value="list" as="template">
			<div
				class="cursor-pointer rounded-l-lg p-1 border border-w-light-grey text-w-light-grey font-medium text-xs uppercase hover:bg-black hover:bg-opacity-5 focus:outline-none focus:ring-0 transition duration-150 ease-in-out"
				:class="{
					'border-w-blue-deep bg-w-light-grey text-w-blue-deep': checked,
				}"
			>
				<ListIcon class="w-7 h-7" />
			</div>
		</RadioGroupOption>
		<RadioGroupOption v-slot="{ checked }" value="grid" as="template">
			<div
				class="cursor-pointer rounded-r-lg p-1 text-w-light-grey border border-w-light-grey font-medium text-xs uppercase hover:bg-black hover:bg-opacity-5 focus:outline-none focus:ring-0 transition duration-150 ease-in-out"
				:class="{
					'border-w-blue-deep bg-w-light-grey text-w-blue-deep': checked,
				}"
			>
				<GridIcon class="w-7 h-7" />
			</div>
		</RadioGroupOption>
	</RadioGroup>
</template>

<script lang="ts">
import { defineComponent } from '@vue/runtime-core';
import { PropType } from 'vue';
import { RadioGroup, RadioGroupOption } from '@headlessui/vue';
import { ListIcon, GridIcon } from '@witcher/icons';

export default defineComponent({
	components: { RadioGroup, RadioGroupOption, ListIcon, GridIcon },
	props: {
		modelValue: {
			type: String as PropType<'list' | 'grid'>,
			required: true,
		},
	},
	emits: ['update:modelValue'],
});
</script>
