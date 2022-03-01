<template>
	<div
		:class="[
			'bg-w-grey-background-light',
			'px-6 py-4 grid grid-cols-[repeat(auto-fit,minmax(250px,1fr))] gap-3',
		]"
	>
		<Card>
			<SmallWidget title="160" subtitle="Consumed" after-title="sp">
				<Consumed />
			</SmallWidget>
		</Card>
		<Card>
			<SmallWidget title="32,5" subtitle="Efficiency rate" after-title="%">
				<Rate />
			</SmallWidget>
		</Card>
		<Card>
			<SmallWidget
				title="550:23"
				subtitle="Total Hours Consumed"
				after-title="hr"
			>
				<Hours />
			</SmallWidget>
		</Card>
		<Card>
			<SmallWidget title="32/123" subtitle="Total tasks completed">
				<Tasks />
			</SmallWidget>
		</Card>
		<Card>
			<SmallWidget title="4.665.00" subtitle="Total earnings" after-title="EUR">
				<Earning />
			</SmallWidget>
		</Card>
	</div>
</template>

<script>
import { SmallWidget, Card } from '@app/components';
import Consumed from './components/consumed.component.vue';
import Rate from './components/rate.component.vue';
import Hours from './components/hours.component.vue';
import Tasks from './components/tasks.component.vue';
import Earning from './components/earning.component.vue';

export default {
	components: { SmallWidget, Card, Consumed, Rate, Hours, Tasks, Earning },
};
</script>
