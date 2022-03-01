<template>
	<div class="bg-white py-3 shadow-md px-6 2xl:px-16">
		<form class="flex items-center gap-2">
			<div
				class="p-2 border border-dashed border-w-blue-deep flex flex-1 gap-4 rounded-lg"
			>
				<Input
					v-model="search"
					type="text"
					name="search"
					placeholder="search for task..."
					input-class="border-transparent"
					class="flex-1"
				/>
				<div class="flex items-center flex-1">
					<label for="activity">Activity</label>
					<Select
						v-model="activity"
						:options="activityList"
						button-border="border-transparent"
						class="w-full ml-2"
					/>
				</div>
				<div class="flex items-center flex-1">
					<label for="tech">Tech</label>
					<Select
						v-model="tech"
						:options="techList"
						button-border="border-transparent"
						class="w-full ml-2"
					/>
				</div>
				<div class="flex items-center flex-1">
					<label for="date">Date</label>
					<Input
						id="date"
						v-model="date"
						type="date"
						name="date"
						class="ml-2"
						input-class="border-transparent pr-3"
					>
						<template #icon-right>
							<CalendarIcon class="w-6 h-6" />
						</template>
					</Input>
				</div>
				<div class="flex items-center flex-1">
					<label for="time">Time invested</label>
					<Input
						id="time"
						v-model="time"
						type="time"
						name="time"
						class="ml-2"
						input-class="border-transparent pr-3"
					>
						<template #icon-right>
							<TimesheetIcon class="w-6 h-6" />
						</template>
					</Input>
				</div>
			</div>
			<button
				class="h-[46px] w-[46px] bg-w-vivid-green flex items-center justify-center rounded-lg"
				type="button"
			>
				<CheckIcon class="text-white w-5 h-5" />
			</button>
		</form>
	</div>
</template>

<script lang="ts">
import { ref } from 'vue';
import { defineComponent } from '@vue/runtime-core';
import { Input, Select } from '@app/components';
import { CalendarIcon, TimesheetIcon, CheckIcon } from '@witcher/icons';

export default defineComponent({
	components: { CalendarIcon, CheckIcon, TimesheetIcon, Input, Select },
	setup() {
		const date = ref('');
		const time = ref('');
		const search = ref('');

		const techList = [{ name: '' }];
		const activityList = [{ name: '' }];
		const tech = ref(techList[0]);
		const activity = ref(activityList[0]);

		return { date, time, search, tech, techList, activity, activityList };
	},
});
</script>
