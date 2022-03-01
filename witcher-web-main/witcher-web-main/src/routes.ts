import { RouteRecordRaw } from 'vue-router';
import { authRoutes } from '@auth/auth.routes';
import { appRoutes } from '@app/app.routes';
import Auth from './auth/auth.component.vue';
import App from './app/app.component.vue';
import { isAuthenticated } from './utils/authentication';

export const routes: RouteRecordRaw[] = [
	{
		path: '/',
		component: App,
		children: appRoutes,
		beforeEnter: async () => {
			const authenticated = await isAuthenticated();
			return authenticated ? true : '/auth';
		},
	},
	{
		path: '/auth',
		component: Auth,
		children: authRoutes,
		beforeEnter: async () => {
			const authenticated = await isAuthenticated();
			return authenticated ? '/' : true;
		},
	},
];
