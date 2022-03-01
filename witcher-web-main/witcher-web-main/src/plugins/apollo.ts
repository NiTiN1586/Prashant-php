import { Plugin } from 'vue';
import { DefaultApolloClient } from '@vue/apollo-composable';
import {
	ApolloClient,
	createHttpLink,
	from,
	InMemoryCache,
	ServerError,
} from '@apollo/client/core';
import { onError } from '@apollo/client/link/error';
import possibleTypes from '../../generated/possible-types';
import { removeAuthentication } from '../utils/authentication';

function isServerError(error: unknown): error is ServerError {
	return (error as ServerError)?.name === 'ServerError';
}

export const apollo: Plugin = (app) => {
	const httpLink = createHttpLink({
		uri: import.meta.env.VITE_API_GRAPHQL,
		credentials: 'include',
	});

	const errorLink = onError(({ networkError }) => {
		if (isServerError(networkError) && networkError.statusCode === 401) {
			removeAuthentication();
			location.pathname = '/auth';
		}
	});

	const apolloClient = new ApolloClient({
		link: from([errorLink, httpLink]),
		cache: new InMemoryCache({ ...possibleTypes }),
	});

	app.provide(DefaultApolloClient, apolloClient);
};
