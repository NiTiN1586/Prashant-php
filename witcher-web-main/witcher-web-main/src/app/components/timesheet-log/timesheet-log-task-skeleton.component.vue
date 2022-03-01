<template>
	<div
		class="bg-w-grey-background-light w-full flex items-stretch gap-1 p-1 animate-pulse"
		:class="{ 'rounded-b': isLast, 'rounded-t': isFirst }"
	>
		<div class="flex items-center flex-grow py-1.5 pr-4 truncate rounded-lg">
			<DropdownIcon
				class="ml-5 mr-4 flex-shrink-0 w-2.5 h-2.5 text-w-grey-icons -rotate-90"
			/>
			<div class="flex items-center truncate flex-grow sm:mr-3">
				<span class="bg-w-light-grey rounded-lg px-1 mr-2 min-w-[25px]">
					&nbsp;
				</span>
				<span
					class="h-2 bg-w-light-grey rounded-full w-[40px] inline-block mr-2"
				/>
				<span
					class="h-2 bg-w-light-grey rounded-full inline-block"
					:class="randomItem(['w-1/2', 'w-1/3', 'w-1/4'])"
				/>
			</div>
		</div>
		<div class="ml-auto flex items-center flex-shrink-0 py-1.5 px-2 rounded-lg">
			<MoreVertIcon class="w-9 h-9 text-w-light-grey" />
		</div>
	</div>
</template>

<script lang="ts" setup>
import { DropdownIcon, MoreVertIcon } from '@witcher/icons';
import { randomItem } from '@witcher/utils/random-item';

defineProps({
	isLast: {
		type: Boolean,
		required: true,
	},
	isFirst: {
		type: Boolean,
		required: true,
	},
});
</script>
