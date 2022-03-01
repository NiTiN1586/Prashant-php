// https://github.com/1milligram/1loc/pull/494
export const intersperse = <T>(array: T[], separator: T): T[] =>
	[...Array(2 * array.length - 1)].map((_, i) =>
		i % 2 ? separator : array[i / 2],
	);
