import { Sprint } from 'generated/types';
import { isBetween } from './date';

type Edge = { node: Pick<Sprint, 'name' | 'startedAt' | 'endedAt'> };
export function getLatestSprintName(
	// Assuming that edges are sorted by `endedAt` in `DESC` order
	edges: ReadonlyArray<Edge>,
): string | undefined {
	const currentSprintEdge = edges.find((edge) =>
		isBetween(
			new Date(),
			new Date(edge.node.startedAt),
			new Date(edge.node.endedAt),
		),
	);

	if (currentSprintEdge) return currentSprintEdge.node.name;

	// TODO: Maybe enable `noUncheckedIndexedAccess` in `tsconfig.json`?
	const [lastEdge] = edges;
	return lastEdge?.node.name;
}
