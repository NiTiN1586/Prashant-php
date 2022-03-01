<template>
	<component
		:is="as"
		:class="[
			'inline-flex items-center justify-center',
			'rounded-lg px-3 py-1.5',
			'text-sm font-semibold tracking-wider',
			'hover:shadow border',
			colors[color][variant],
		]"
	>
		<span class="truncate">
			<slot name="default" />
		</span>
		<span v-if="$slots['icon-right']" class="ml-2">
			<slot name="icon-right" />
		</span>
		<!-- Compensate icon height when missing -->
		<span v-else class="h-7" />
	</component>
</template>

<!-- Do not use script setup, storybook at the moment is not able to recognise -->
<script lang="ts">
import { defineComponent, PropType } from '@vue/runtime-core';

type Variant = 'contained' | 'outlined';
type As = 'button' | 'a';
type Color = 'blue' | 'danger' | 'green';

const colors: Record<Color, Record<Variant, string>> = {
	blue: {
		contained: 'border-w-blue-deep bg-w-blue-deep text-white',
		outlined: 'border-w-blue-deep text-w-blue-deep',
	},
	danger: {
		contained: 'border-transparent bg-w-pink/5 text-w-pink',
		outlined: 'border-transparent text-w-pink',
	},
	green: {
		contained: 'border-w-vivid-green bg-w-vivid-green text-white',
		outlined: 'border-w-vivid-green text-w-vivid-green',
	},
};

export default defineComponent({
	props: {
		variant: {
			type: String as PropType<Variant>,
			default: 'contained',
		},
		color: {
			type: String as PropType<Color>,
			default: 'blue',
		},
		as: {
			type: String as PropType<As>,
			default: 'button',
		},
	},
	setup: () => ({ colors }),
});
</script>
