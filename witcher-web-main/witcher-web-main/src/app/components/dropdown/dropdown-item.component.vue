<template>
	<MenuItem v-slot="{ active }" as="template">
		<li
			class="text-sm"
			:class="active ? 'text-gray-900 bg-gray-100' : 'text-gray-900'"
		>
			<slot />
		</li>
	</MenuItem>
</template>

<script lang="ts" setup>
import { MenuItem } from '@headlessui/vue';
</script>
