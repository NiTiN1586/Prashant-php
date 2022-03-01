const storageKey = 'authenticated';

function isAuthenticatedStorage(): boolean {
	const storageAuthenticated = localStorage.getItem(storageKey);

	try {
		return storageAuthenticated
			? JSON.parse(storageAuthenticated) === true
			: false;
	} catch {
		return false;
	}
}

export function removeAuthentication() {
	localStorage.removeItem(storageKey);
}

export async function isAuthenticated() {
	if (isAuthenticatedStorage()) {
		return true;
	}

	const api = import.meta.env.VITE_API_REST;
	const url = new URL(
		`${api}/_user-provider/authentication/last-result`,
		location.origin,
	);
	const response = await fetch(url.href, { credentials: 'include' });
	const authenticated = response.status === 200;

	if (authenticated) {
		localStorage.setItem(storageKey, JSON.stringify(authenticated));
	}

	return authenticated;
}
