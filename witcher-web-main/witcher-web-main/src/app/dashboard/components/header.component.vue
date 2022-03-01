<template>
	<section class="relative">
		<div
			class="bg-gradient-to-r from-w-gradient-button-1 to-w-gradient-button-2 shadow relative rounded-br-none rounded text-white pr-4 md:pt-2 lg:pt-0"
		>
			<Row>
				<Column class="md:w-1/5 lg:w-[10%] xl:w-[10%]">
					<Avatar
						v-if="userInfo.avatar"
						class="relative lg:-mb-40 xl:-mb-36 2xl:ml-[8px]"
						size="h-[5.563rem] w-[5.563rem]"
						alt="profile image"
						:src="userInfo.avatar"
					/>
					<ProfilePicPlaceholder
						v-else
						class="rounded-full inline-block relative h-[5.563rem] w-[5.563rem] lg:-mb-40 xl:-mb-36 ml-4 lg:ml-2 xl:ml-4 2xl:ml-[8px]"
					/>
				</Column>
				<Column class="my-auto lg:w-[55%] xl:w-[53%]">
					<h3 class="text-sm font-normal leading-6 md:-ml-8 lg:ml-0 2xl:ml-4">
						{{ t('dashboard.user.greet.morning') }}
					</h3>
					<h1
						class="font-medium text-[16px] inline md:-ml-8 xl:text-[22px] lg:ml-0 2xl:ml-4"
						v-text="userInfo.name"
					></h1>
					<div class="xl:inline-flex font-medium md:-ml-8 lg:ml-0 2xl:ml-4">
						<div class="list-item text-xl ml-8">
							<PostIcon class="inline -mt-1 mr-2 w-6 h-6" />
							<span class="text-sm" v-text="userInfo.post"></span>
						</div>
						<div class="list-item text-xl ml-8">
							<TeamsIcon class="inline -mt-1 mr-2 w-6 h-6" />
							<span class="text-sm" v-text="userInfo.branch"></span>
						</div>
					</div>
				</Column>
				<Column class="lg:w-[35%] xl:w-[37%]">
					<div
						class="lg:-ml-16 xl:ml-0 2xl:ml-auto md:ml-[20rem] md:-mt-28 lg:mt-0"
					>
						<div class="relative text-center mt-4 z-10">
							<div class="font-medium text-[18px] text-right mt-8">
								<span v-text="userInfo.timeFrom"></span>
								<span class="font-semibold text-sm"> to </span
								><span v-text="userInfo.timeTo"></span>
								<Toggle
									v-model="userStatus"
									class="inline-block align-text-top -mb-8 ml-4 font-normal text-[13px] text-center"
								/>
								<div class="font-normal text-[13px] text-right">
									<span class="mr-[6.7rem]">
										<TimezoneIcon class="inline mt-[-3px] w-4 h-4" />
										{{ userInfo.timezone }}
									</span>
									<span
										class="inline mr-[3px]"
										v-text="
											userStatus == true
												? t('dashboard.user.status.online')
												: t('dashboard.user.status.offline')
										"
									></span>
								</div>
							</div>
						</div>

						<RightBackgroundTwo
							class="w-full ml-4 md:h-28 xl:h-24 mt-[-78.5px] relative md:rounded-br-none md:rounded"
						/>
						<RightBackgroundOne
							class="w-full -ml-4 mt-[-101px] hidden xl:block"
						/>
					</div>
				</Column>
			</Row>
		</div>
		<div class="text-[#333984] rounded bg-[#F8F8FF] -mt-5">
			<Row>
				<Column class="w-full text-center pb-8 mt-12">
					<div
						v-for="(stats, index) in userInfo.stats"
						:key="index"
						class="w-full inline-block mt-4 text-[#333984] md:w-1/5 md:mt-4 2xl:w-[11%]"
						:class="index === 0 ? 'md:ml-1' : 'md:ml-8'"
					>
						<component
							:is="stats.icon"
							class="text-w-blue-deep mx-auto mb-2 w-8 h-8"
						/>
						<span class="text-[22px] font-medium" v-text="stats.value"></span>
						<h3 class="text-[14px] font-normal mt-1" v-text="stats.label"></h3>
						<div
							v-if="index > 0"
							class="w-[2px] h-[38px] hidden -mt-16 -ml-4 bg-[#C4D6F7] md:block"
						/>
					</div>
				</Column>
			</Row>
		</div>
	</section>
</template>

<script lang="ts">
import { useI18n } from 'vue-i18n';
import { ref, defineComponent } from 'vue';
import {
	FeedbackIcon,
	InvoicesIcon,
	MeterIcon,
	PostIcon,
	ProjectsIcon,
	TasksIcon,
	TeamsIcon,
	TimezoneIcon,
} from '@witcher/icons';
import { Column, Row } from '@witcher/components';
import { Avatar, ProfilePicPlaceholder, Toggle } from '@app/components';
import RightBackgroundOne from './right-background-one.component.vue';
import RightBackgroundTwo from './right-background-two.component.vue';

export default defineComponent({
	components: {
		Row,
		Column,
		Avatar,
		PostIcon,
		TeamsIcon,
		RightBackgroundOne,
		RightBackgroundTwo,
		TimezoneIcon,
		Toggle,
		MeterIcon,
		ProfilePicPlaceholder,
	},
	setup() {
		const { t } = useI18n();
		const userStatus = ref(true);

		const userInfo = {
			avatar: '',
			name: 'John Smith',
			post: 'Front-end Dev',
			branch: 'Factory branch',
			timeFrom: '10:00h',
			timeTo: '18:00h',
			timezone: 'GMT',
			stats: [
				{
					label: t('dashboard.stats.weeklyTasks'),
					value: '32',
					icon: ProjectsIcon,
				},
				{
					label: t('dashboard.stats.urgentTasks'),
					value: '10',
					icon: TasksIcon,
				},
				{
					label: t('dashboard.stats.projects'),
					value: '5',
					icon: ProjectsIcon,
				},
				{
					label: t('dashboard.stats.spPoints'),
					value: '162',
					icon: ProjectsIcon,
				},
				{
					label: t('dashboard.stats.overtime'),
					value: '1:15h',
					icon: MeterIcon,
				},
				{
					label: t('dashboard.stats.salary'),
					value: '3.651,12',
					icon: InvoicesIcon,
				},
				{
					label: t('dashboard.stats.feedback'),
					value: '32',
					icon: FeedbackIcon,
				},
			],
		};
		return { t, userStatus, userInfo };
	},
});
</script>
