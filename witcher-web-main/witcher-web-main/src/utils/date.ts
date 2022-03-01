/** https://1loc.dev/validator/check-if-a-date-is-between-two-dates */
export function isBetween(date: Date, min: Date, max: Date): boolean {
	return date.getTime() >= min.getTime() && date.getTime() <= max.getTime();
}
