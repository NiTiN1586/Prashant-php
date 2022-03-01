<template>
	<component
		:is="tag"
		class="bg-w-blue-deep rounded-lg text-white px-2.5 py-1 mr-2"
	>
		<slot />
	</component>
</template>

<script lang="ts">
import { defineComponent } from '@vue/runtime-core';
import { PropType } from 'vue';

export default defineComponent({
	props: {
		tag: {
			type: String as PropType<string>,
			default: 'span',
		},
	},
});
</script>
