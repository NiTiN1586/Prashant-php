<template>
	<div class="border border-w-light-grey rounded">
		<div id="graph">
			<div class="flex gap-6 justify-center p-5">
				<div class="inline-grid items-center justify-items-center">
					<SvgCircle
						:stroke-width="14"
						:diameter="210"
						:total="100"
						:current="65"
						class="row-span-full col-span-full"
					/>
					<SvgCircle
						:stroke-width="14"
						:diameter="160"
						:total="redialData.totalTask"
						:current="redialData.completedTask"
						class="row-span-full col-span-full"
						foreground-color="text-w-tech-green"
					/>
					<div
						class="row-span-full col-span-full flex flex-col justify-center items-center"
					>
						<div class="flex items-center">
							<span class="text-2xl font-bold">
								{{ redialData.completedTask }}
							</span>
							<span>/</span>
							<span>{{ redialData.totalTask }}</span>
						</div>
						<div class="text-sm text-w-grey-icons text-center">
							Weekly<br />
							tasks
						</div>
					</div>
				</div>
			</div>
			<div class="flex justify-center pt-4 px-3 mb-4">
				<div class="flex mr-12 items-center text-w-dark-blue">
					<div class="w-3 h-3 bg-w-charts-background rounded-full mr-3"></div>
					<div class="mr-1 text-2xl font-semibold">46h</div>
					<div class="text-sm">Weekly work</div>
				</div>
				<div class="flex items-center text-w-dark-blue">
					<div class="w-3 h-3 bg-w-tech-green rounded-full mr-3"></div>
					<div class="mr-1 text-2xl font-semibold">6h</div>
					<div class="text-sm">Today's. work</div>
				</div>
			</div>
		</div>
		<div
			class="grid grid-cols-2 divide-x divide-w-light-grey border-t border-w-light-grey"
		>
			<SmallWidget title="32" subtitle="Total SP points consumed" class="p-6">
				<svg
					width="48"
					height="48"
					viewBox="0 0 48 48"
					fill="none"
					xmlns="http://www.w3.org/2000/svg"
				>
					<rect
						width="48"
						height="48"
						rx="24"
						fill="#FF005C"
						fill-opacity="0.05"
					/>
					<path
						d="M31.2 14.666L26.4 19.6438H30V28.3549C30 29.7238 28.92 30.8438 27.6 30.8438C26.28 30.8438 25.2 29.7238 25.2 28.3549V19.6438C25.2 16.8936 23.052 14.666 20.4 14.666C17.748 14.666 15.6 16.8936 15.6 19.6438V28.3549H12L16.8 33.3327L21.6 28.3549H18V19.6438C18 18.2749 19.08 17.1549 20.4 17.1549C21.72 17.1549 22.8 18.2749 22.8 19.6438V28.3549C22.8 31.1051 24.948 33.3327 27.6 33.3327C30.252 33.3327 32.4 31.1051 32.4 28.3549V19.6438H36L31.2 14.666Z"
						fill="#FF005C"
					/>
				</svg>
			</SmallWidget>
			<SmallWidget title="5%" subtitle="Efficiency rate" class="p-6">
				<svg
					width="49"
					height="48"
					viewBox="0 0 49 48"
					fill="none"
					xmlns="http://www.w3.org/2000/svg"
				>
					<rect
						x="0.5"
						width="48"
						height="48"
						rx="24"
						fill="#F2C94C"
						fill-opacity="0.1"
					/>
					<path
						fill-rule="evenodd"
						clip-rule="evenodd"
						d="M18.8787 21.4766C19.5126 21.4766 20.0265 20.9634 20.0265 20.3303C20.0265 19.6973 19.5126 19.1841 18.8787 19.1841C18.2448 19.1841 17.7309 19.6973 17.7309 20.3303C17.7309 20.9634 18.2448 21.4766 18.8787 21.4766ZM18.8787 23.3393C20.5428 23.3393 21.8918 21.9921 21.8918 20.3303C21.8918 18.6685 20.5428 17.3214 18.8787 17.3214C17.2146 17.3214 15.8656 18.6685 15.8656 20.3303C15.8656 21.9921 17.2146 23.3393 18.8787 23.3393Z"
						fill="#F4A827"
					/>
					<path
						fill-rule="evenodd"
						clip-rule="evenodd"
						d="M29.536 26.8913C30.17 26.8913 30.6839 26.3781 30.6839 25.7451C30.6839 25.112 30.17 24.5988 29.536 24.5988C28.9021 24.5988 28.3882 25.112 28.3882 25.7451C28.3882 26.3781 28.9021 26.8913 29.536 26.8913ZM29.536 28.754C31.2001 28.754 32.5491 27.4069 32.5491 25.7451C32.5491 24.0833 31.2001 22.7361 29.536 22.7361C27.8719 22.7361 26.5229 24.0833 26.5229 25.7451C26.5229 27.4069 27.8719 28.754 29.536 28.754Z"
						fill="#F4A827"
					/>
					<path
						fill-rule="evenodd"
						clip-rule="evenodd"
						d="M15.5131 32.804C16.147 32.804 16.6609 32.2908 16.6609 31.6577C16.6609 31.0247 16.147 30.5115 15.5131 30.5115C14.8791 30.5115 14.3652 31.0247 14.3652 31.6577C14.3652 32.2908 14.8791 32.804 15.5131 32.804ZM15.5131 34.6667C17.1772 34.6667 18.5262 33.3195 18.5262 31.6577C18.5262 29.9959 17.1772 28.6488 15.5131 28.6488C13.849 28.6488 12.5 29.9959 12.5 31.6577C12.5 33.3195 13.849 34.6667 15.5131 34.6667Z"
						fill="#F4A827"
					/>
					<path
						fill-rule="evenodd"
						clip-rule="evenodd"
						d="M32.1536 16.1552C32.7875 16.1552 33.3014 15.642 33.3014 15.0089C33.3014 14.3759 32.7875 13.8627 32.1536 13.8627C31.5196 13.8627 31.0057 14.3759 31.0057 15.0089C31.0057 15.642 31.5196 16.1552 32.1536 16.1552ZM32.1536 18.0179C33.8177 18.0179 35.1667 16.6707 35.1667 15.0089C35.1667 13.3471 33.8177 12 32.1536 12C30.4895 12 29.1405 13.3471 29.1405 15.0089C29.1405 16.6707 30.4895 18.0179 32.1536 18.0179Z"
						fill="#F4A827"
					/>
					<path
						fill-rule="evenodd"
						clip-rule="evenodd"
						d="M32.5894 17.0618L30.8132 23.9392L29.0071 23.474L30.7833 16.5966L32.5894 17.0618ZM27.3358 25.7808L20.0127 22.0465L20.861 20.3876L28.1841 24.1219L27.3358 25.7808ZM15.1807 29.4145L17.3621 22.1327L19.1491 22.6665L16.9677 29.9484L15.1807 29.4145Z"
						fill="#F4A827"
					/>
				</svg>
			</SmallWidget>
		</div>
		<div id="progress" class="border-t p-6 border-w-light-grey">
			<p class="text-base mb-8">Most consuming activities</p>
			<div v-for="(item, index) in progressData" :key="index" class="mt-6">
				<div class="flex justify-between mb-2 text-sm tracking-wide">
					<span>{{ item.title }}</span>
					<strong class="font-semibold">{{ item.value }}</strong>
				</div>
				<ProgressCard :progress="item.progress"></ProgressCard>
			</div>
		</div>
		<div id="tabs" class="py-5 px-6 border-t border-w-light-grey">
			<div class="flex items-center mb-4 justify-between">
				<p class="text-base text-blue-900">Recent Activity</p>
				<a class="text-sm text-gray-400 cursor-pointer">View All</a>
			</div>
			<div class="flex mb-3">
				<button
					class="bg-gray-100 px-3 text-sm py-1 rounded-lg mr-3"
					@click="tab = 1"
				>
					All
				</button>
				<button class="text-gray-400 text-sm px-3" @click="tab = 2">
					My Activity
				</button>
			</div>
			<div v-if="tab === 1" class="divide-y divide-w-light-grey">
				<div
					v-for="activity in activityData"
					:key="activity.id"
					class="flex mb-4 pt-4"
				>
					<Avatar alt="image" :src="activity.avatar" class="mr-3" />
					<div>
						<p class="text-sm text-gray-400">{{ activity.time }}</p>
						<div class="flex mb-5 items-center">
							<p class="font-semibold py-1">{{ activity.name }}</p>
							<p
								class="bg-gray-100 px-2 text-sm py-1 rounded-lg mx-2 text-blue-400"
							>
								{{ activity.comment }}
							</p>
							on
							<p
								class="bg-gray-100 px-2 text-sm py-1 rounded-lg ml-2 text-blue-400"
							>
								{{ activity.ticket }}
							</p>
						</div>
						<div>
							<p>{{ activity.comment }}</p>
						</div>
					</div>
				</div>
			</div>
			<div v-if="tab === 2">
				<h5>My Activity</h5>
			</div>
		</div>
	</div>
</template>

<script lang="ts" setup>
import { ref } from 'vue';
import ProgressCard from '../../../components/progress/progress.component.vue';
import SmallWidget from '../../../components/small-widget/small-widget.component.vue';
import Avatar from '../../../components/avatar/avatar.component.vue';
import SvgCircle from '../../../components/svg-circle/svg-circle.component.vue';

const tab = ref(1);

const activityData = [
	{
		id: '1',
		avatar:
			'https://cdn.pixabay.com/photo/2015/04/23/22/00/tree-736885__480.jpg',
		name: 'Kyati Bordila',
		time: '5 hours ago',
		desc: 'The client loves project',
		comment: 'Good Work',
		ticket: '#TUI-89',
	},
	{
		id: '2',
		avatar:
			'https://cdn.pixabay.com/photo/2015/04/23/22/00/tree-736885__480.jpg',
		name: 'Kyati Bordila',
		time: '5 hours ago',
		desc: 'The client loves project',
		comment: 'Good Work',
		ticket: '#TUI-89',
	},
];

const progressData = [
	{ title: 'Development', value: '13:05 Hrs', progress: 10 },
	{ title: 'Meeting', value: '15:05 Hrs', progress: 50 },
	{ title: 'UI/UX Design', value: '23:05 Hrs', progress: 30 },
];
const redialData = {
	totalTask: 375,
	completedTask: 20,
};
</script>
