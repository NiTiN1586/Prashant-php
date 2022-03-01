<template>
	<div class="relative" :class="$props.class">
		<div
			v-if="$slots['icon-left']"
			:class="[
				'absolute inset-y-0 left-0 pl-3',
				'flex items-center pointer-events-none',
			]"
		>
			<slot name="icon-left" />
		</div>
		<input
			v-bind="$attrs"
			:value="modelValue"
			:class="[
				'block w-full py-2 rounded-lg',
				'text-sm placeholder:text-w-grey-icons border-w-light-grey',
				'focus:border-w-blue-deep focus:ring-w-blue-deep',
				{
					'pl-10': $slots['icon-left'],
					'pr-10': $slots['icon-right'],
				},
				inputClass,
			]"
			@input="$emit('update:modelValue', $event.target.value)"
		/>
		<div
			v-if="$slots['icon-right']"
			:class="[
				'absolute inset-y-0 right-0 pr-3',
				'flex items-center pointer-events-none',
			]"
		>
			<slot name="icon-right" />
		</div>
	</div>
</template>

<script lang="ts">
import { defineComponent, PropType } from 'vue';

// Why Vue does not provide something like this?!
type ClassType = string | Record<string, boolean> | Array<ClassType>;

export default defineComponent({
	inheritAttrs: false,
	props: {
		modelValue: {
			type: String as PropType<string>,
			default: '',
		},
		// in template use $props.class instead `class` because is a keyword
		class: {
			type: [String, Object, Array] as PropType<ClassType>,
			default: undefined,
		},
		inputClass: {
			type: [String, Object, Array] as PropType<ClassType>,
			default: undefined,
		},
	},
	emits: ['update:modelValue'],
});
</script>
