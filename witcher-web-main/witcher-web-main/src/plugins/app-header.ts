import { inject, InjectionKey, Plugin, ref } from 'vue';

export const getTitle = () => {
	const title = ref<string>();
	function setTitle(text: string) {
		title.value = text;
	}

	return { title, setTitle };
};

type AppHeader = ReturnType<typeof getTitle>;
const injectionKey: InjectionKey<AppHeader> = Symbol('app-header');

export function useAppHeader() {
	const value = inject(injectionKey);
	if (value) {
		return value;
	}
	throw new Error('useAppHeader must be used within `app-header` plugins');
}

export const appHeader: Plugin = (app) => {
	app.provide(injectionKey, getTitle());
};
