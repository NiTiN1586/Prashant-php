/**
 * Normalize the ratio of a number in a range
 * https://1loc.dev/math/normalize-the-ratio-of-a-number-in-a-range/
 */
export function normalizeRatio(
	value: number,
	min: number,
	max: number,
): number {
	return (value - min) / (max - min);
}
