<template>
	<DatePicker
		mode="date"
		:model-value="modelValue"
		:is-required="isRequired"
		:popover="{ visibility: visibility, positionFixed: true }"
		:masks="{ input: format }"
		@update:model-value="$emit('update:modelValue', $event)"
	>
		<template #default="{ inputValue, inputEvents, togglePopover }">
			<div class="flex">
				<!-- TODO: Think how to reuse input component -->
				<!-- TODO: or maybe better to externalize it -->
				<input
					class="appearance-none border indent-2 rounded w-full text-gray-700 leading-tight focus:outline-none"
					:class="inputClass"
					:value="inputValue"
					v-bind="inputAttr"
					v-on="inputEvents"
				/>
				<!-- TODO: Do we need icon inside input? Maybe pass through slot? -->
				<CalendarIcon
					v-if="showIcon"
					class="w-7 h-7 inline cursor-pointer outline-0"
					@click="togglePopover()"
				/>
			</div>
		</template>
	</DatePicker>
</template>

<script lang="ts" setup>
import { PropType } from '@vue/runtime-core';
import { DatePicker } from 'v-calendar';
import { CalendarIcon } from '@witcher/icons';
import 'v-calendar/dist/style.css';

defineEmits(['update:modelValue']);
defineProps({
	modelValue: {
		type: Date as PropType<Date>,
		default: () => new Date(),
	},
	inputClass: {
		type: String as PropType<String>,
		default: '',
	},
	format: {
		type: String as PropType<String>,
		default: 'DD/MM/YYYY',
	},
	visibility: {
		type: String as PropType<String>,
		default: 'click',
	},
	inputAttr: {
		type: Object as PropType<Object>,
		default: null,
	},
	showIcon: {
		type: Boolean as PropType<Boolean>,
		default: true,
	},
	isRequired: {
		type: Boolean as PropType<Boolean>,
		default: false,
	},
});
</script>
