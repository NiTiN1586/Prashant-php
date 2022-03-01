import { defineComponent } from 'vue';
import { Meta, Story } from '@storybook/vue3';
import { faker } from '@faker-js/faker';
import { getPicsumImage } from '@witcher/utils/get-picsum-image';
import { capitalize } from '@witcher/utils/capitalize';
import { action } from '@storybook/addon-actions';
import { pageOptions } from '@witcher/utils/paging';
import TimesheetLogProject from './timesheet-log-project.component.vue';
import TimesheetLogTask from './timesheet-log-task.component.vue';
import TimesheetLogActivity from './timesheet-log-activity.component.vue';
import TimesheetLogProjectSkeleton from './timesheet-log-project-skeleton.component.vue';
import TimesheetLogTaskSkeleton from './timesheet-log-task-skeleton.component.vue';
import TimesheetLogProjectFooter from './timesheet-log-project-footer.component.vue';
import TimesheetLogActivitySkeleton from './timesheet-log-activity-skeleton.component.vue';

export default {
	title: 'Components / Timesheet Log',
	subcomponents: {
		TimesheetLogProject,
		TimesheetLogTask,
		TimesheetLogActivity,
		TimesheetLogProjectSkeleton,
		TimesheetLogTaskSkeleton,
		TimesheetLogProjectFooter,
	},
} as Meta;

const html = String.raw;

export const TimesheetLog: Story = () => {
	return defineComponent({
		components: {
			TimesheetLogProject,
			TimesheetLogTask,
			TimesheetLogActivity,
		},
		setup: () => ({
			projects,
			activityTypes,
			technologies,
			repositories,
			onDelete: action('Deleted'),
			onActivityType: action('Activity Type'),
			onTechnology: action('Technology'),
			onRepository: action('Repository'),
			onEstimationTime: action('EstimationTime'),
		}),
		template: html`
			<TimesheetLogProject
				v-for="project in projects"
				:key="project.id"
				class="mb-6"
				:name="project.name"
				:totalTasks="project.totalTasks"
				:image="project.image"
			>
				<TimesheetLogTask
					v-for="(task, indexTask) in project.tasks"
					:key="task.id"
					:summary="task.summary"
					:slug="task.slug"
					:is-first="indexTask === 0"
					:is-last="indexTask === project.tasks.length - 1"
					:total-activities="task.totalActivities"
					:user-name="task.userName"
					:user-avatar="task.userAvatar"
				>
					<TimesheetLogActivity
						v-for="activity in task.activities"
						:key="activity.id"
						:activity-types="activityTypes"
						:activity-type="activity.activityType"
						@update:activity-type="onActivityType"
						:technologies="technologies"
						:technology="activity.technology"
						@update:technology="onTechnology"
						:repositories="repositories"
						:repository="activity.repository"
						@update:repository="onRepository"
						:phase="activity.phase"
						:milestone="activity.milestone"
						:sprint="activity.sprint"
						:created-date="activity.createdDate"
						:estimationTime="activity.estimationTime"
						@update:estimationTime="onEstimationTime"
						@delete="onDelete(activity.id)"
					></TimesheetLogActivity>
				</TimesheetLogTask>
			</TimesheetLogProject>
		`,
	});
};

const activityTypes = Array.from({ length: 5 }, (_) => ({
	id: faker.datatype.uuid(),
	friendlyName: capitalize(faker.lorem.word()),
}));

const technologies = Array.from({ length: 5 }, (_) => ({
	id: faker.datatype.uuid(),
	friendlyName: faker.lorem.word().toUpperCase(),
}));

const repositories = Array.from({ length: 5 }, (_) => ({
	id: faker.datatype.uuid(),
	friendlyName: capitalize(faker.lorem.word()),
}));

const projects = Array.from({ length: 5 }, (_, indexProject) => {
	const slug = faker.lorem.slug(1).toUpperCase();
	const toLoad = faker.datatype.number(10);
	return {
		id: faker.datatype.uuid(),
		name: faker.commerce.productName(),
		image: getPicsumImage(1 + indexProject),
		totalTasks: toLoad > 0 ? faker.datatype.number(999) : toLoad,
		tasks: Array.from({ length: toLoad }, (_, indexTask) => {
			const toLoad = faker.datatype.number(10);
			return {
				id: faker.datatype.uuid(),
				slug: `${slug}-${faker.datatype.number(999)}`,
				summary: faker.lorem.sentence(),
				userName: `${faker.name.firstName()} ${faker.name.lastName()}`,
				userAvatar: getPicsumImage(1 + indexProject + indexTask),
				totalActivities: toLoad > 0 ? faker.datatype.number(150) : toLoad,
				activities: Array.from({ length: toLoad }, () => ({
					id: faker.datatype.uuid(),
					// TODO: TBD
					comment: faker.lorem.paragraph(),
					activityType: faker.random.arrayElement(activityTypes),
					technology: faker.random.arrayElement(technologies),
					repository: faker.random.arrayElement(repositories),
					phase: `${faker.datatype.number(5)}º Phase`,
					milestone: faker.commerce.productName(),
					sprint: `Sprint ${faker.datatype.number(10)}`,
					createdDate: faker.date.past(0, '2022-01-01').toISOString(),
					// min 1 minute, max 2 hours
					estimationTime: faker.datatype.number({ min: 60, max: 7200 }),
				})),
			};
		}),
	};
});

export const ProjectSkeleton: Story = () => {
	return defineComponent({
		components: { TimesheetLogProjectSkeleton },
		template: html`
			<div>
				<TimesheetLogProjectSkeleton v-for="i in 10" :key="i" class="mb-6" />
			</div>
		`,
	});
};

export const TaskSkeleton: Story = () => {
	return defineComponent({
		components: {
			TimesheetLogTaskSkeleton,
			TimesheetLogProject,
			TimesheetLogProjectFooter,
		},
		setup() {
			return {
				project: projects[0],
				options: pageOptions,
			};
		},
		template: html`
			<TimesheetLogProject
				:name="project.name"
				:totalTasks="project.totalTasks"
				:image="project.image"
				:default-open="true"
			>
				<TimesheetLogTaskSkeleton
					v-for="i in 10"
					:key="i"
					:is-first="i === 1"
					:is-last="i === 10"
				></TimesheetLogTaskSkeleton>
				<TimesheetLogProjectFooter
					:initial-per-page="options[0]"
					:page-options="options"
				/>
			</TimesheetLogProject>
		`,
	});
};

export const ActivitySkeleton: Story = () => {
	return defineComponent({
		components: {
			TimesheetLogTask,
			TimesheetLogActivitySkeleton,
		},
		setup() {
			return {
				project: projects[0],
				task: projects[0].tasks[0],
			};
		},
		template: html`
			<TimesheetLogTask
				:key="task.id"
				:summary="task.summary"
				:slug="task.slug"
				:is-first="indexTask === 0"
				:is-last="indexTask === project.tasks.length - 1"
				:total-activities="task.totalActivities"
				:user-name="task.userName"
				:user-avatar="task.userAvatar"
				:default-open="true"
			>
				<TimesheetLogActivitySkeleton
					v-for="i in 5"
					:key="i"
					:is-first="i === 1"
					:is-last="i === 5"
				></TimesheetLogActivitySkeleton>
			</TimesheetLogTask>
		`,
	});
};
