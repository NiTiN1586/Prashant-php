import { RouteRecordRaw, RouterView } from 'vue-router';
import { projectsRoutes } from '@projects/projects.routes';
import { tasksRoutes } from '@tasks/tasks.routes';
import Dashboard from './dashboard/dashboard.component.vue';
import Timesheet from './timesheet/timesheet.component.vue';
import NotFound from './not-found.component.vue';

export const appRoutes: RouteRecordRaw[] = [
	{ path: '/:pathMatch(.*)*', component: NotFound },
	{ path: '', component: Dashboard },
	{ path: 'projects', component: RouterView, children: projectsRoutes },
	{ path: 'timesheet', component: Timesheet },
	{ path: 'tasks', component: RouterView, children: tasksRoutes },
];
