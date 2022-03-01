/// <reference types="vite/client" />

declare module '*.vue' {
	import { DefineComponent } from 'vue';
	// eslint-disable-next-line @typescript-eslint/no-explicit-any, @typescript-eslint/ban-types
	const component: DefineComponent<{}, {}, any>;
	export default component;
}

interface ImportMetaEnv extends Readonly<Record<string, string>> {
	readonly VITE_API_REST: string;
	readonly VITE_API_GRAPHQL: string;
}

interface ImportMeta {
	readonly env: ImportMetaEnv;
}

// https://github.com/frenic/csstype#what-should-i-do-when-i-get-type-errors
declare module 'csstype' {
	interface Properties {
		'--tw-shadow': string;
	}
}
