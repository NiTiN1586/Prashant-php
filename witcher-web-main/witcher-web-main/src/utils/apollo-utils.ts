export function isTemporaryNode(node: { id: string }): boolean {
	return node.id.startsWith('temporary-');
}

export function getTemporaryId(): string {
	return `temporary-${Math.random().toString()}`;
}
