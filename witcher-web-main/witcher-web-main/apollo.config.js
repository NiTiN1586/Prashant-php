/**
 * This file configuration is only for Visual Studio Code at the moment
 * https://v4.apollo.vuejs.org/guide/installation.html#visual-studio-code
 */
module.exports = {
	client: {
		service: {
			name: 'Witcher',
			localSchemaFile: './schema.graphql',
		},
		includes: ['./src/**/*.graphql'],
	},
};
