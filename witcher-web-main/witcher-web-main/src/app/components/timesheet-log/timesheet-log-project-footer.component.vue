<template>
	<div class="p-4 mr-auto text-right">
		<Select
			v-model="perPage"
			:class="{
				'opacity-70	 cursor-not-allowed': disabled,
			}"
			class="w-20 mr-4"
			:options="pageOptions"
			:disabled="disabled"
		/>
		<Button
			:class="{
				'opacity-70 cursor-not-allowed': disabled,
			}"
			:disabled="disabled"
			@click="loadMore"
			>Load {{ perPage.name }} more tasks</Button
		>
	</div>
</template>

<script lang="ts">
import { defineComponent, PropType, ref } from 'vue';
import { Select, Button } from '@witcher/app/components';

export default defineComponent({
	components: { Select, Button },
	props: {
		disabled: {
			type: Boolean as PropType<Boolean>,
			default: false,
		},
		pageOptions: {
			type: Array as PropType<ReadonlyArray<Record<string, unknown>>>,
			required: true,
		},
		initialPerPage: {
			type: Object as PropType<Record<string, unknown>>,
			required: true,
		},
	},
	emits: ['loadMore'],
	setup(props, { emit }) {
		const perPage = ref(props.initialPerPage);

		const loadMore = () => {
			emit('loadMore', perPage.value);
		};

		return { perPage, loadMore };
	},
});
</script>
