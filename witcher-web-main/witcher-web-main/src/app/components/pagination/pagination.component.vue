<template>
	<div class="flex">
		<button
			:disabled="!hasPrevious"
			class="border border-blue-100 rounded-l-lg p-1.5 cursor-pointer"
			:aria-label="t('common.previous')"
			@click="goToPage(currentPage - 1)"
		>
			<LeftArrow />
		</button>
		<button
			v-for="item in pagination"
			:key="item.page"
			:class="item.page === currentPage ? 'bg-blue-50' : ''"
			class="border text-blue-900 border-blue-100 p-1.5 cursor-pointer"
			@click="goToPage(item.page)"
		>
			<span class="m-2">{{ item.text }}</span>
		</button>
		<button
			:disabled="!hasNext"
			class="border border-blue-100 rounded-r-lg p-1.5 cursor-pointer"
			:aria-label="t('common.next')"
			@click="goToPage(currentPage + 1)"
		>
			<RightArrow />
		</button>
	</div>
</template>
<script lang="ts">
import { defineComponent, computed } from '@vue/runtime-core';
import { useI18n } from 'vue-i18n';
import LeftArrow from './components/left-arrow.component.vue';
import RightArrow from './components/right-arrow.component.vue';

export default defineComponent({
	components: { LeftArrow, RightArrow },
	props: {
		currentPage: {
			type: Number,
			required: true,
		},
		lastPage: {
			type: Number,
			required: true,
		},
	},
	emits: ['update:currentPage'],

	setup(props, { emit }) {
		const { t } = useI18n();
		const hasPrevious = computed(() => props.currentPage > 1);
		const hasNext = computed(() => props.currentPage < props.lastPage);

		const pagination = computed(() =>
			paginationWithDots(props.currentPage, props.lastPage),
		);

		const goToPage = (page: number) => emit('update:currentPage', page);

		return {
			t,
			hasPrevious,
			hasNext,
			pagination,
			goToPage,
		};
	},
});

function paginationWithDots(currentPage: number, lastPage: number) {
	const delta = 2;
	const left = currentPage - delta;
	const right = currentPage + delta + 1;
	const range: number[] = [];
	const rangeWithDots: { page: number; text: string }[] = [];
	let l;

	for (let i = 1; i <= lastPage; i++) {
		if (i === 1 || i === lastPage || (i >= left && i < right)) {
			range.push(i);
		}
	}

	for (const i of range) {
		if (l) {
			if (i - l === 2) {
				rangeWithDots.push({ page: l + 1, text: String(l + 1) });
			} else if (i - l !== 1) {
				rangeWithDots.push({ text: '...', page: l + 1 });
			}
		}
		rangeWithDots.push({ page: i, text: String(i) });
		l = i;
	}

	return rangeWithDots;
}
</script>
