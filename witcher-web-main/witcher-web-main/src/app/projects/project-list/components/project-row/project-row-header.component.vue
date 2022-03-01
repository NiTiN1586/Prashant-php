<template>
	<tr class="text-left text-sm whitespace-nowrap">
		<th class="pl-12 pr-3">
			<SortButton>Name</SortButton>
		</th>
		<th class="px-3">
			<SortButton>Project Type</SortButton>
		</th>
		<th class="px-3">
			<SortButton>Manager</SortButton>
		</th>
		<th class="px-3">
			<SortButton>Leader</SortButton>
		</th>
		<th class="px-3">
			<SortButton>Client</SortButton>
		</th>
		<th class="px-3">
			<SortButton>SP</SortButton>
		</th>
		<th class="px-3">
			<SortButton>Effic.</SortButton>
		</th>
		<th class="px-3">
			<SortButton>Phase</SortButton>
		</th>
		<th class="pr-12 pl-3">
			<SortButton>Progress</SortButton>
		</th>
	</tr>
</template>

<script lang="ts">
import { defineComponent } from '@vue/runtime-core';
import { SortButton } from '@app/components';

export default defineComponent({
	components: { SortButton },
});
</script>
