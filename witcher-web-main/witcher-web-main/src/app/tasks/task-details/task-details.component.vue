<template>
	<div v-if="loading">{{ t('common.loading') }}</div>
	<div v-else-if="error">{{ t('common.somethingWrong') }}</div>
	<div v-else-if="task">
		<Breadcrumbs>
			<RouterLink :to="{ name: 'task-list' }">Task List</RouterLink>
			<RouterLink
				:to="{
					name: 'project-details',
					params: { id: task.witcherProject.id },
				}"
			>
				{{ task.witcherProject.slug }}
			</RouterLink>
			<span>{{ task.slug }}</span>
		</Breadcrumbs>

		<Row>
			<Column class="w-full lg:w-4/6">
				<span
					:style="{
						backgroundColor: `${task.priority.statusColor}0C`,
						color: task.priority.statusColor,
					}"
					class="mt-10 mb-6 px-3 py-1 text-sm rounded-lg inline-flex items-center gap-2"
				>
					<span
						:style="{
							backgroundColor: task.priority.statusColor,
						}"
						class="inline-block rounded-full w-1 h-[14px]"
					/>
					{{ task.priority.friendlyName }}
				</span>
				<h1 class="text-2xl">{{ task.summary }}</h1>
				<Sanitize
					v-if="task.description"
					:html="task.description"
					class="my-6"
				/>

				<div class="my-6 flex items-center gap-6">
					<PageTitle :count="activities.length" title="Activities" />
					<Button class="ml-auto" @click="createActivity">
						Add activity
						<template #icon-right>
							<AddCircleIcon class="w-7 h-7" />
						</template>
					</Button>
				</div>

				<div class="overflow-x-auto my-6">
					<table class="w-full border-separate [border-spacing:0_10px]">
						<thead>
							<tr class="text-left text-sm">
								<th class="pr-2.5 pb-6">
									<SortButton>{{ t('common.activityType') }}</SortButton>
								</th>
								<th class="px-2.5 pb-6">
									<SortButton>{{ t('common.technology') }}</SortButton>
								</th>
								<th class="px-2.5 pb-6 text-center">
									<SortButton>{{ t('common.source') }}</SortButton>
								</th>
								<th class="px-2.5 pb-6 text-center">
									<SortButton>{{ t('common.date') }}</SortButton>
								</th>
								<th class="px-2.5 pb-6 text-center">
									<SortButton>{{ t('common.time') }}</SortButton>
								</th>
								<th class="px-2.5 pb-6 text-center">
									<SortButton>{{ t('common.storyPointsShort') }}</SortButton>
								</th>
								<th />
							</tr>
						</thead>
						<tbody>
							<TaskActivity
								v-for="activity in activities"
								:key="activity.id"
								:activity="activity"
								:activity-types="activityTypes"
								@deleted="onDelete"
							/>
							<TableRow class="relative">
								<TableData
									is-first
									is-last
									class="border-dashed p-5 text-center text-w-blue-deep text-sm"
									colspan="7"
								>
									<button
										class="after:absolute after:inset-0"
										@click="createActivity"
									>
										Add new activity
									</button>
								</TableData>
							</TableRow>
						</tbody>
					</table>
				</div>
			</Column>
			<Column class="hidden lg:block lg:w-2/6">
				<div class="border rounded p-6 border-w-light-grey">
					<div class="grid items-center justify-items-center my-3">
						<SvgCircle
							:stroke-width="30"
							:diameter="211"
							:current="60"
							class="row-span-full col-span-full"
						/>
						<div class="row-span-full col-span-full text-center">
							<div class="text-[28px] font-bold">9:32h</div>
							<div class="text-sm">Total task<br />time</div>
						</div>
					</div>
					<div class="grid gap-2 grid-cols-3 text-center my-10">
						<div>
							<div class="text-sm flex gap-2 items-center justify-center">
								<span class="w-3 h-3 rounded-full bg-w-charts-background" />
								<span class="truncate">Weekly work</span>
							</div>
							<div class="font-semibold text-[26px]">46h</div>
						</div>
						<div>
							<div class="text-sm flex gap-2 items-center justify-center">
								<span class="w-3 h-3 rounded-full bg-w-blue-deep" />
								<span class="truncate">Today work</span>
							</div>
							<div class="font-semibold text-[26px]">6h</div>
						</div>
						<div>
							<div class="text-sm flex gap-2 items-center justify-center">
								<span class="w-3 h-3 rounded-full bg-w-vivid-green" />
								<span class="truncate">Efficiency rate</span>
							</div>
							<div class="font-semibold text-[26px]">20%</div>
						</div>
					</div>
					<div class="grid gap-4 grid-cols-2 mt-10 mb-5">
						<Button variant="outlined" :title="sprintName">
							<span>{{ sprintName }}</span>
						</Button>
						<Button
							variant="outlined"
							as="a"
							:href="task.externalTrackerLink"
							target="_blank"
						>
							<span>Jira Link</span>
							<template #icon-right>
								<ExternalLinkIcon class="w-6 h-6" />
							</template>
						</Button>
					</div>
					<ul class="divide-y divide-w-light-grey text-sm">
						<li class="py-5 flex justify-between items-center">
							<span>Reporter</span>
							<span class="flex gap-2 items-center">
								<span>Davide Bernardo</span>
								<Avatar :src="getPicsumImage(100)" alt="" />
							</span>
						</li>
						<li class="py-5 flex justify-between">
							<span>Created on</span>
							<span>{{ new Date(task.createdAt).toLocaleDateString() }}</span>
						</li>
						<li class="py-5 flex justify-between items-center">
							<span>Project</span>
							<RouterLink
								:to="{
									name: 'project-details',
									params: { id: task.witcherProject.id },
								}"
								class="flex items-center gap-2 text-w-blue-deep"
							>
								{{ task.witcherProject.name }}
								<ExternalLinkIcon class="w-4 h-4" />
							</RouterLink>
						</li>
						<li class="py-5 flex justify-between items-center">
							<span>Team</span>
							<span>
								<Avatar
									:src="getPicsumImage(101)"
									alt=""
									class="border-2 border-white"
								/>
								<Avatar
									:src="getPicsumImage(102)"
									alt=""
									class="border-2 border-white -ml-4"
								/>
								<Avatar
									:src="getPicsumImage(103)"
									alt=""
									class="border-2 border-white -ml-4"
								/>
							</span>
						</li>
						<li v-if="task.labels.edges.length > 0" class="py-5">
							<div class="mb-3">Tasks labels</div>
							<div class="flex gap-3">
								<span
									v-for="label in task.labels.edges"
									:key="label.node.id"
									class="bg-w-grey-background-light px-3 py-2 rounded-lg"
								>
									{{ label.node.name }}
								</span>
							</div>
						</li>
					</ul>
				</div>
			</Column>
		</Row>
	</div>
</template>

<script lang="ts" setup>
import { useRoute, RouterLink } from 'vue-router';
import { useI18n } from 'vue-i18n';
import { useResult } from '@vue/apollo-composable';
import { Sanitize } from '@witcher/utils/sanitize';
import { getPicsumImage } from '@witcher/utils/get-picsum-image';
import { Column, Row } from '@witcher/components';
import {
	Avatar,
	Breadcrumbs,
	Button,
	PageTitle,
	SortButton,
	SvgCircle,
	TableData,
	TableRow,
} from '@app/components';
import { AddCircleIcon, ExternalLinkIcon } from '@witcher/icons';
import { getTemporaryId } from '@witcher/utils/apollo-utils';
import { randomItem } from '@witcher/utils/random-item';
import { getLatestSprintName } from '@witcher/utils/sprint';
import {
	useTaskDetailsQuery,
	TaskDetailsQuery,
} from './graphql/task-details.query.generated';
import TaskActivity from './components/task-activity.component.vue';
import { useActivityCreateMutation } from './graphql/activity-create.mutation.generated';

const route = useRoute();
const { t } = useI18n();
const { loading, error, result } = useTaskDetailsQuery({
	id: route.params.id as string,
});

const task = useResult(result, undefined, (data) => data.task);
const activities = useResult(result, [], (data) =>
	data.task.activities.edges.map((e) => e.node),
);
const sprintName = useResult(result, 'None', (data) =>
	getLatestSprintName(data.task.sprints.edges),
);

type ActivityType = TaskDetailsQuery['activityTypes']['edges'][number]['node'];
const activityTypes = useResult(result, [] as ActivityType[], (data) =>
	data.activityTypes.edges.map((e) => e.node),
);

function onDelete(cache: any, { data }: any) {
	if (!task.value) return;
	cache.modify({
		id: cache.identify(task.value),
		fields: {
			activities: (connection: any, { readField }: any) => ({
				...connection,
				edges: connection.edges.filter((edge: any) => {
					const id = readField('id', edge.node);
					return data?.deleteActivity.activity.id !== id;
				}),
			}),
		},
	});
}

const activityCreateMutation = useActivityCreateMutation({
	update(cache, { data }) {
		if (!task.value) return;

		const { clientMutationId: id, activity } = data?.createActivity ?? {};

		cache.modify({
			id: cache.identify(task.value),
			fields: {
				activities: (connection) => ({
					...connection,
					edges: [
						// filter temporary created
						...connection.edges.filter((e: any) => e.node.id !== id),
						{ __typename: 'ActivityEdge', node: activity },
					],
				}),
			},
		});
	},
	optimisticResponse: (vars) => ({
		__typename: 'Mutation',
		createActivity: {
			clientMutationId: vars.input.clientMutationId,
			__typename: 'createActivityPayload',
			activity: {
				__typename: 'Activity',
				id: vars.input.clientMutationId,
				estimationTime: vars.input.estimationTime,
				estimationSp: vars.input.estimationSp,
				activityType: activityTypes.value[0],
			},
		},
	}),
});

function createActivity() {
	if (!task.value || !activityTypes.value[0]) return;
	// TODO: Take into consideration project estimation type
	const temporaryId = getTemporaryId();
	const activityType = randomItem(activityTypes.value);

	activityCreateMutation.mutate(
		{
			input: {
				clientMutationId: temporaryId,
				// after creation user can change
				estimationTime: 0,
				estimationSp: 0,
				// no comment in UI
				comment: '',
				// after creation user can change
				activityType: activityType.id,
				task: task.value.id,
				activityAt: new Date().toISOString(),
			},
		},
		{
			optimisticResponse: {
				__typename: 'Mutation',
				createActivity: {
					clientMutationId: temporaryId,
					__typename: 'createActivityPayload',
					activity: {
						__typename: 'Activity',
						id: temporaryId,
						estimationTime: 0,
						estimationSp: 0,
						activityType,
					},
				},
			},
		},
	);
}
</script>
