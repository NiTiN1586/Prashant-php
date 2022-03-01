<template>
	<ul
		:class="[
			'inline-flex gap-4 items-center overflow-x-auto max-w-full',
			'text-sm',
		]"
	>
		<VirtualNodes :nodes="breadcrumbs" />
	</ul>
</template>

<script lang="ts" setup>
import { Fragment, h, isVNode, useSlots, VNode, Comment } from 'vue';
import { intersperse } from '@witcher/utils/intersperse';
import { VirtualNodes } from '@witcher/utils/virtual-nodes.component';
import { computed, PropType } from '@vue/runtime-core';
import { ForwardIcon } from '@witcher/icons';

const slots = useSlots();
const props = defineProps({
	separator: {
		type: Object as PropType<VNode>,
		default: h(ForwardIcon, { class: 'w-6 h-6' }),
	},
});

const breadcrumbs = computed(() => {
	const allNodes = slots.default?.() ?? [];
	// unwrap fragments when using v-for
	const nodes = allNodes.flatMap((n) => (n.type === Fragment ? n.children : n));
	const validNodes = nodes.filter(isVNode).filter((n) => n.type !== Comment);
	const separatedNodes = intersperse(validNodes, props.separator);
	return separatedNodes.map((n) => h('li', { class: 'min-w-fit' }, n));
});
</script>
