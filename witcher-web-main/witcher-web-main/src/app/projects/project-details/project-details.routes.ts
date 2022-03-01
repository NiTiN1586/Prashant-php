import { RouteRecordRaw } from 'vue-router';
import ProjectOverview from './project-overview/project-overview.component.vue';
import ProjectTasks from './project-tasks/project-tasks.component.vue';
import ProjectTechnologies from './project-technologies/project-technologies.component.vue';
import ProjectTeam from './project-team/project-team.component.vue';
import ProjectEdit from './project-edit/project-edit.component.vue';
import ProjectTimeline from './project-timeline/project-timeline.component.vue';

export const projectDetailsRoutes: RouteRecordRaw[] = [
	{ name: 'project-details', path: '', component: ProjectOverview },
	{ name: 'project-tasks', path: 'tasks', component: ProjectTasks },
	{
		name: 'project-technologies',
		path: 'technologies',
		component: ProjectTechnologies,
	},
	{ name: 'project-team', path: 'team', component: ProjectTeam },
	{ name: 'project-edit', path: 'edit', component: ProjectEdit },
	{ name: 'project-timeline', path: 'timeline', component: ProjectTimeline },
];
