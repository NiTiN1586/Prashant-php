import { inject, InjectionKey, Plugin } from 'vue';
import { useLocalStorage } from '@vueuse/core';

export const getSettings = () => {
	const isMenuOpen = useLocalStorage('settings.is-menu-open', true);
	function toggleMenu() {
		isMenuOpen.value = !isMenuOpen.value;
	}

	function hideMenu() {
		if (window.innerWidth < 1280) {
			isMenuOpen.value = false;
		}
	}

	return { isMenuOpen, toggleMenu, hideMenu };
};

type Settings = ReturnType<typeof getSettings>;
const injectionKey: InjectionKey<Settings> = Symbol('settings');

export function useSettings() {
	const value = inject(injectionKey);
	if (value) {
		return value;
	}
	throw new Error('useSettings must be used within `settings` plugins');
}

export const settings: Plugin = (app) => {
	app.provide(injectionKey, getSettings());
};
