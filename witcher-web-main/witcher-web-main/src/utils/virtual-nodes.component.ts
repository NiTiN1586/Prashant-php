import { VNode } from 'vue';

type Props = {
	nodes: VNode[] | VNode;
};

export function VirtualNodes(props: Props) {
	return props.nodes;
}
