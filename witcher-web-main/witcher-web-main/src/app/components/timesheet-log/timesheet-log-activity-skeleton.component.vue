<template>
	<tr class="rounded text-sm bg-white animate-pulse">
		<td class="pl-16 py-0">
			<span class="h-6 bg-w-light-grey rounded-full w-32 inline-block mr-2">
				<DropdownIcon
					class="mr-3 mt-[6px] ml-auto flex-shrink-0 w-2.5 h-2.5 text-w-grey-icons"
				/>
			</span>
		</td>
		<td class="pl-12 py-0">
			<span class="h-6 bg-w-light-grey rounded-full w-28 inline-block mr-2">
				<DropdownIcon
					class="mr-3 mt-[6px] ml-auto flex-shrink-0 w-2.5 h-2.5 text-w-grey-icons"
				/>
			</span>
		</td>
		<td class="py-0 pl-6">
			<span class="h-6 bg-w-light-grey rounded-full w-28 inline-block mr-2">
				<DropdownIcon
					class="mr-3 mt-[6px] ml-auto flex-shrink-0 w-2.5 h-2.5 text-w-grey-icons"
				/>
			</span>
		</td>
		<td class="py-0">
			<span class="h-2 bg-w-light-grey rounded-full w-20 inline-block mr-2" />
		</td>
		<td class="py-0 max-w-xs">
			<span class="h-2 bg-w-light-grey rounded-full w-40 inline-block mr-2" />
		</td>
		<td class="py-0 px-6">
			<span class="h-2 bg-w-light-grey rounded-full w-12 inline-block mr-2" />
		</td>
		<td class="pl-8 py-0">
			<span class="h-2 bg-w-light-grey rounded-full w-20 inline-block mr-2">
			</span>
			<CalendarIcon
				class="w-6 h-6 ml-auto mr-2 inline flex-shrink-0 text-w-grey-icons"
			/>
		</td>
		<td class="pl-12">
			<span class="h-2 bg-w-light-grey rounded-full w-12 inline-block mr-2" />
		</td>
		<td class="pl-8">
			<Input
				type="time"
				input-class="w-[70px] flex-shrink-0 bg-w-light-grey text-center font-semibold"
				disabled
			/>
		</td>
		<td class="pl-8">
			<CloseIcon class="w-6 h-6 text-w-light-grey" />
		</td>
	</tr>
</template>

<script lang="ts" setup>
import { DropdownIcon, CalendarIcon, CloseIcon } from '@witcher/icons';
import { Input } from '@app/components';
</script>
