import { Meta, Story } from '@storybook/vue3';
import { defineComponent } from '@vue/runtime-core';
import { getPicsumImage } from '@witcher/utils/get-picsum-image';
import { faker } from '@faker-js/faker';
import { Container } from '../../../components';
import Component from './user-card.component.vue';

export default {
	title: 'Components / User Card',
	component: Component,
} as Meta;

const html = String.raw;

export const UserCard: Story = () => {
	return defineComponent({
		components: { UserCard: Component, Container },
		setup() {
			return { userList };
		},
		template: html`
			<Container
				class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4"
			>
				<UserCard
					v-for="user in userList"
					:key="user.id"
					:name="user.name"
					:profileImage="user.profileImage"
					:email="user.email"
					:date="user.date"
					:totalProjects="user.totalProjects"
					:totalTasks="user.totalTasks"
					:designation="user.designation"
					:isTeamLeader="user.isTeamLeader"
					:disabled="user.disabled"
				/>
			</Container>
		`,
	});
};

declare interface User {
	date: string;
	totalProjects: number;
	name: string;
	isTeamLeader: boolean;
	disabled: boolean;
	id: string;
	profileImage: string;
	designation: string;
	email: string;
	totalTasks: number;
}

const userList: User[] = Array.from({ length: 5 }, (_, indexProject) => {
	return {
		id: faker.datatype.uuid(),
		name: `${faker.name.firstName()} ${faker.name.lastName()}`,
		profileImage: getPicsumImage(1 + indexProject, 100, 100),
		date: `${faker.datatype.number(31)} sp`,
		email: `${faker.name.firstName()}@${faker.name.lastName()}.io`,
		totalProjects: faker.datatype.number(999),
		totalTasks: faker.datatype.number(999),
		designation: faker.name.jobTitle(),
		isTeamLeader: faker.datatype.boolean(),
		disabled: faker.datatype.boolean(),
	};
});
