module.exports = {
	root: true,
	extends: [
		'@jagaad/eslint-config-vue',
		'plugin:storybook/recommended',
		'prettier',
	],
	rules: {
		'@typescript-eslint/no-unused-vars': [
			'error',
			{ args: 'all', argsIgnorePattern: '^_', varsIgnorePattern: '^h$' },
		],
	},
	overrides: [
		{
			files: ['**/*.stories.ts'],
			rules: {
				'vue/one-component-per-file': 'off',
			},
		},
	],
};
