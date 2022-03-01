/**
 * Returns a number whose value is limited to the given range, just like clamp.
 * The difference is that clamp does return the lower or upper limit if
 * the value is outside the range. Instead, wrap returns the value wrapped
 * around the range.
 */
export function wrap(num: number, min: number, max: number): number {
	const rangeSize = max - min;
	return ((((num - min) % rangeSize) + rangeSize) % rangeSize) + min;
}
