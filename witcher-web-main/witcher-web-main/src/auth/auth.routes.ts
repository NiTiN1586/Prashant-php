import { RouteRecordRaw } from 'vue-router';
import SignIn from './sign-in.component.vue';
import ForgotPassword from './forgot-password.component.vue';
import UpdatePassword from './update-password.component.vue';

export const authRoutes: RouteRecordRaw[] = [
	{ path: '/:pathMatch(.*)*', redirect: '/auth' },
	{ path: '', redirect: '/auth/sign-in' },
	{ component: SignIn, path: 'sign-in' },
	{ component: ForgotPassword, path: 'forgot-password' },
	{ component: UpdatePassword, path: 'update-password' },
];
