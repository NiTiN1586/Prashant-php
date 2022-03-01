<template>
	<td
		:class="[
			'border-t border-b border-w-light-grey whitespace-nowrap',
			{
				'border-l rounded-l': isFirst,
				'border-r rounded-r': isLast,
			},
		]"
	>
		<slot />
	</td>
</template>

<script lang="ts">
import { defineComponent, PropType } from '@vue/runtime-core';

export default defineComponent({
	props: {
		isFirst: {
			type: Boolean as PropType<boolean>,
			default: false,
		},
		isLast: {
			type: Boolean as PropType<boolean>,
			default: false,
		},
	},
});
</script>
