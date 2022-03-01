import { RouteRecordRaw } from 'vue-router';
// TODO: Move this component in `task-list/task-list.component.vue`
import TaskList from './task-list/task-list.component.vue';
import TaskDetails from './task-details/task-details.component.vue';

export const tasksRoutes: RouteRecordRaw[] = [
	{ name: 'task-list', path: '', component: TaskList },
	{ name: 'task-details', path: ':id', component: TaskDetails },
];
