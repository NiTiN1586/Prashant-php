import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';
import vueJsx from '@vitejs/plugin-vue-jsx';
import eslint from '@rollup/plugin-eslint';
import tsconfigPaths from 'vite-tsconfig-paths';

// https://vitejs.dev/config/
export default defineConfig({
	define: {
		// https://vue-i18n.intlify.dev/guide/advanced/optimization.html#reduce-bundle-size-with-feature-build-flags
		__VUE_I18N_FULL_INSTALL__: true,
		__VUE_I18N_LEGACY_API__: false,
		__INTLIFY_PROD_DEVTOOLS__: false,
	},
	plugins: [
		tsconfigPaths(),
		vue(),
		vueJsx(),
		eslint({
			// TODO: Report issue in vite repo?
			// Vite pipes every file through eslint plugin
			// Eslint cannot understand all the files
			exclude: [
				// @ts-ignore
				/node_modules/,
				// @ts-ignore
				/vue&type=style/,
				'index.html',
				// @ts-ignore
				/vite\/modulepreload-polyfill/,
				// @ts-ignore
				/\.json$/,
				// @ts-ignore
				/\.generated\.ts$/,
			],
		}),
	],
});
