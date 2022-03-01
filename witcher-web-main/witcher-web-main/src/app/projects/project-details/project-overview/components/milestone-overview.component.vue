<template>
	<div class="rounded bg-white p-6 shadow">
		<h3 class="mb-4">Milestones Overview</h3>
		<ul>
			<li
				v-for="i in 3"
				:key="i"
				class="py-2 px-2 border-b flex items-center border-w-light-grey"
			>
				<span class="inline-block w-2 h-2 bg-w-blue-deep rounded" />
				<span class="text-sm ml-3">Milestone {{ i }}</span>
				<span class="ml-auto text-xl text-w-blue-deep font-medium"> 30% </span>
				<span class="ml-3 text-sm">100h</span>
			</li>
		</ul>
	</div>
</template>
