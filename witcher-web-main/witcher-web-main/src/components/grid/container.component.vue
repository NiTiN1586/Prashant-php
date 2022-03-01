<template>
	<div :class="['mx-auto', padding, { [variant]: !fluid }]">
		<slot></slot>
	</div>
</template>

<script lang="ts" setup>
import { PropType } from 'vue';

defineProps({
	fluid: {
		type: Boolean as PropType<boolean>,
		default: () => false,
	},
	variant: {
		type: String as PropType<string>,
		default: () => 'container',
	},
	padding: {
		type: String as PropType<string>,
		default: () => 'px-4',
	},
});
</script>
