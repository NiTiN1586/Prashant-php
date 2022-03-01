import 'tailwindcss/tailwind.css';
import { createApp } from 'vue';
import { createRouter, createWebHistory, RouterView } from 'vue-router';
import { routes } from './routes';
import { settings } from './plugins/settings';
import { apollo } from './plugins/apollo';
import { appHeader } from './plugins/app-header';
import { i18n } from './i18n';

const router = createRouter({
	history: createWebHistory(),
	routes,
});

createApp(RouterView)
	.use(router)
	.use(i18n)
	.use(apollo)
	.use(settings)
	.use(appHeader)
	.mount('#app');
