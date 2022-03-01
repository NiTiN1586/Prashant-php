<template>
	<section class="relative mt-4">
		<Row>
			<Column class="w-1/4" />
			<Column class="w-3/4">
				<div
					v-for="(x, index) in axis"
					:key="index"
					class="w-1/5 inline-block font-normal text-sm"
					v-text="`${x}%`"
				></div>
				<div class="w-1/5 inline-block -ml-2 font-normal text-sm">
					80%
					<div class="text-right -mt-6">100%</div>
				</div>
			</Column>
		</Row>
		<div class="p-4 rounded">
			<Disclosure
				v-for="(technology, index) in technologies"
				:key="index"
				v-slot="{ open }"
				as="div"
			>
				<Row
					:class="open ? 'bg-w-grey-background-light border-b-2' : ''"
					class="py-2 rounded"
				>
					<Column class="w-1/4 inline-flex my-auto">
						<DisclosureButton
							:class="!open ? 'border border-w-light-grey' : ''"
							class="bg-white rounded py-4 px-4"
						>
							<Avatar
								:src="technology.avatar"
								class="inline mr-4 h-8 w-8"
								alt=""
							/>
							<UserIcon class="inline w-4 h-4" />
							<span
								class="ml-2 mr-4 text-sm font-normal"
								v-text="`${technology.users.length} users`"
							></span>
							<SelectorIcon class="inline w-6 h-6" />
						</DisclosureButton>
					</Column>
					<Column class="w-3/4 inline-flex my-auto">
						<LabeledProgress
							:progress="technology.percentage"
							:label="`${technology.hours}h`"
						/>
					</Column>
				</Row>
				<DisclosurePanel>
					<Row
						v-for="(user, userIndex) in technology.users"
						:key="userIndex"
						:class="
							userIndex !== technology.users.length - 1 ? 'border-b-2' : ''
						"
						class="py-2 bg-w-grey-background-light"
					>
						<Column class="w-1/4 inline-flex my-auto font-normal">
							<Avatar
								:src="user.avatar"
								alt="User avatar"
								class="mr-4"
								size="h-[32px] w-[32px]"
							/>
							<span class="text-sm mr-4 my-auto" v-text="user.name"></span>
							<span
								class="text-[13px] text-w-blue-deep my-auto"
								v-text="user.post"
							></span>
						</Column>
						<Column class="w-3/4 inline-flex my-auto">
							<LabeledProgress
								:progress="user.percentage"
								:label="`${user.hours}h`"
								size="h-[20px]"
								background-color="bg-[#E1E5FE]"
								indicator-classes="bg-w-blue-deep relative mt-[.4rem] mr-2"
							/>
						</Column>
					</Row>
				</DisclosurePanel>
			</Disclosure>
		</div>
	</section>
</template>

<script lang="ts">
import { defineComponent } from '@vue/runtime-core';
import { Disclosure, DisclosureButton, DisclosurePanel } from '@headlessui/vue';
import { useI18n } from 'vue-i18n';
import { getPicsumImage } from '@witcher/utils/get-picsum-image';
import { Avatar, LabeledProgress } from '@app/components';
import { SelectorIcon, UserIcon } from '@witcher/icons';
import { Column, Row } from '@witcher/components';

export default defineComponent({
	components: {
		Row,
		Column,
		Disclosure,
		DisclosureButton,
		DisclosurePanel,
		UserIcon,
		SelectorIcon,
		Avatar,
		LabeledProgress,
	},
	setup() {
		const { t } = useI18n();
		const axis = [0, 20, 40, 60];
		const technologies = [
			{
				avatar: getPicsumImage(6),
				userCount: 3,
				percentage: 80,
				hours: 325,
				users: [
					{
						avatar: getPicsumImage(1),
						name: 'Emily Son',
						post: 'Frontend',
						percentage: 20,
						hours: 100,
					},
					{
						avatar: getPicsumImage(2),
						name: 'Tom Andy',
						post: 'BE',
						percentage: 40,
						hours: 220,
					},
					{
						avatar: getPicsumImage(3),
						name: 'Joana Mile',
						post: 'Engi',
						percentage: 60,
						hours: 350,
					},
				],
			},
			{
				avatar: getPicsumImage(8),
				userCount: 3,
				percentage: 60,
				hours: 250,
				users: [
					{
						avatar: getPicsumImage(1),
						name: 'Emily Son',
						post: 'Frontend',
						percentage: 20,
						hours: 100,
					},
					{
						avatar: getPicsumImage(2),
						name: 'Tom Andy',
						post: 'BE',
						percentage: 40,
						hours: 220,
					},
					{
						avatar: getPicsumImage(3),
						name: 'Joana Mile',
						post: 'Engi',
						percentage: 60,
						hours: 350,
					},
				],
			},
		];

		return { t, axis, technologies };
	},
});
</script>
