<template>
	<img
		:src="src"
		:alt="alt"
		class="object-cover inline-block"
		:class="[size, rounded]"
	/>
</template>

<script lang="ts">
import { defineComponent } from '@vue/runtime-core';
import { PropType } from 'vue';

export default defineComponent({
	props: {
		src: {
			type: String as PropType<string>,
			required: true,
		},
		alt: {
			type: String as PropType<string>,
			required: true,
		},
		size: {
			type: String as PropType<string>,
			default: 'w-9 h-9',
		},
		rounded: {
			type: String as PropType<string>,
			default: 'rounded-full',
		},
	},
});
</script>
