<template>
	<tr class="text-left text-sm whitespace-nowrap text-blue-900">
		<th class="px-3">
			<SortButton>Ref/Name</SortButton>
		</th>
		<th class="px-3">
			<SortButton>Project</SortButton>
		</th>
		<th class="px-3">
			<SortButton>Phase</SortButton>
		</th>
		<th class="px-3">
			<SortButton>Milestone</SortButton>
		</th>
		<th class="px-3">
			<SortButton>Sprint</SortButton>
		</th>
		<th class="px-3">
			<SortButton>SP</SortButton>
		</th>
		<th class="px-3">
			<SortButton>Effi.</SortButton>
		</th>
	</tr>
</template>

<script lang="ts">
import { defineComponent } from '@vue/runtime-core';
import { SortButton } from '../../components';

export default defineComponent({
	components: { SortButton },
});
</script>
