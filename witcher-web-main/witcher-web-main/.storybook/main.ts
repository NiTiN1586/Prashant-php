const { TsconfigPathsPlugin } = require('tsconfig-paths-webpack-plugin');

module.exports = {
	stories: ['../src/**/*.stories.mdx', '../src/**/*.stories.@(js|jsx|ts|tsx)'],
	addons: [
		'@storybook/addon-links',
		'@storybook/addon-essentials',
		'@storybook/addon-postcss',
	],
	webpackFinal(config: any) {
		const extensions = config.resolve.extensions;
		const plugin = new TsconfigPathsPlugin({ extensions });

		// https://github.com/storybookjs/storybook/issues/15990#issuecomment-921208988
		config.module.rules.push({
			test: /\.mjs$/,
			include: /node_modules/,
			type: 'javascript/auto',
		});

		return {
			...config,
			resolve: {
				...config.resolve,
				plugins: [...config.resolve.plugins, plugin],
			},
		};
	},
};
