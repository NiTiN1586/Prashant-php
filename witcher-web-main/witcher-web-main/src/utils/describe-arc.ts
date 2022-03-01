/** https://stackoverflow.com/a/18473154 */
export function describeArc(
	x: number,
	y: number,
	radius: number,
	startAngle: number,
	endAngle: number,
) {
	const start = polarToCartesian(x, y, radius, endAngle);
	const end = polarToCartesian(x, y, radius, startAngle);
	const largeArcFlag = endAngle - startAngle <= 180 ? '0' : '1';
	const move = `M ${start.x} ${start.y}`;
	const arc = `A ${radius} ${radius} 0 ${largeArcFlag} 0 ${end.x} ${end.y}`;

	return `${move} ${arc}`;
}

function polarToCartesian(
	centerX: number,
	centerY: number,
	radius: number,
	angleInDegrees: number,
) {
	const angleInRadians = ((angleInDegrees - 90) * Math.PI) / 180.0;

	return {
		x: centerX + radius * Math.cos(angleInRadians),
		y: centerY + radius * Math.sin(angleInRadians),
	};
}
