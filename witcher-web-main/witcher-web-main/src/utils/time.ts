export function formatDuration(
	duration: number,
	options: { seconds?: boolean } = {},
) {
	options.seconds ??= false;
	const h = String(Math.floor(duration / 3600)).padStart(2, '0');
	const m = String(Math.floor((duration % 3600) / 60)).padStart(2, '0');
	const s = String(Math.floor((duration % 3600) % 60)).padStart(2, '0');
	return options.seconds ? [h, m, s].join(':') : [h, m].join(':');
}

export function timeToDuration(time: string): number {
	const [h = 0, m = 0, s = 0] = time.split(':').map((p) => Number.parseInt(p));
	return h * 3600 + m * 60 + s;
}
