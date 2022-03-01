export const randomItem = <T>(arr: readonly T[]): T =>
	arr[(Math.random() * arr.length) | 0];
