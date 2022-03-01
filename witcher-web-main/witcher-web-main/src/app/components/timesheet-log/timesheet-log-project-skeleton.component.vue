<template>
	<div
		class="border-w-light-grey border rounded text-sm bg-white animate-pulse"
	>
		<div class="flex items-stretch w-full gap-1 rounded">
			<div class="flex items-center flex-grow truncate rounded-l py-2">
				<div class="ml-6 mr-4 flex-shrink-0">
					<DropdownIcon class="w-2.5 h-2.5 text-w-light-grey -rotate-90" />
				</div>
				<div
					class="hidden sm:inline-block bg-w-light-grey w-12 h-12 rounded-full mr-6"
				/>
				<div class="flex flex-grow items-center truncate sm:mr-3">
					<div
						class="h-2 bg-w-light-grey rounded-full"
						:class="randomItem(['w-1/2', 'w-1/3', 'w-1/4'])"
					/>
				</div>
			</div>
			<div class="flex items-center flex-shrink-0 py-2 px-3 rounded-r">
				<MoreVertIcon class="w-9 h-9 text-w-light-grey" />
			</div>
		</div>
	</div>
</template>

<script lang="ts" setup>
import { DropdownIcon, MoreVertIcon } from '@witcher/icons';
import { randomItem } from '@witcher/utils/random-item';
</script>
