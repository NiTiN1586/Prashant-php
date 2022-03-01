import { RouteRecordRaw } from 'vue-router';
import ProjectList from './project-list/project-list.component.vue';
import ProjectDetails from './project-details/project-details.component.vue';
import { projectDetailsRoutes } from './project-details/project-details.routes';

export const projectsRoutes: RouteRecordRaw[] = [
	{ name: 'project-list', path: '', component: ProjectList },
	{
		path: ':id',
		component: ProjectDetails,
		children: projectDetailsRoutes,
	},
];
