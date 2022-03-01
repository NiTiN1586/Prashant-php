export const capitalize = ([first, ...rest]: string): string =>
	`${first.toUpperCase()}${rest.join('')}`;
