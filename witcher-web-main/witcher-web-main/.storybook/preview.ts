import 'tailwindcss/tailwind.css';
import { Parameters, app } from '@storybook/vue3';
import vueRouter from 'storybook-vue3-router';
import { faker } from '@faker-js/faker';
import { DecoratorFunction } from '@storybook/addon-actions';
import { i18n } from '../src/i18n';
import { settings } from '../src/plugins/settings';
import { appHeader } from '../src/plugins/app-header';
import { FunctionalComponent, h } from 'vue';

// Generate same values each time
faker.seed(0);

app.use(i18n).use(settings).use(appHeader);

const noop: FunctionalComponent = () => h('div');
noop.displayName = 'noop';

export const decorators: DecoratorFunction[] = [
	vueRouter([
		{ name: 'project-details', path: '', component: noop },
		{ name: 'task-details', path: '/:id', component: noop },
	]),
];

export const parameters: Parameters = {
	options: {
		storySort: (a: any, b: any) =>
			a[1].kind === b[1].kind
				? 0
				: a[1].id.localeCompare(b[1].id, undefined, { numeric: true }),
	},
};
