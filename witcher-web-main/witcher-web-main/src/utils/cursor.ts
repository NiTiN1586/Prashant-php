export function toCursor(id: number): string {
	return window.btoa(String(id));
}

export function fromCursor(cursor: string): number {
	return Number(window.atob(cursor));
}

export function previousCursor(id: number): string {
	return toCursor(id - 1);
}

export function afterCursor(page: number, perPage: number) {
	return previousCursor(page * perPage);
}

/**
 * @example
 * // return cursor moved 4 units forward
 * shiftCursor(someCursor, 4)
 * @example
 * // return cursor moved 4 units backward
 * shiftCursor(someCursor, -4)
 * @example
 * // returns same cursor
 * shiftCursor(someCursor, 0)
 */
export function shiftCursor(cursor: string, offset: number): string {
	return toCursor(fromCursor(cursor) + offset);
}
