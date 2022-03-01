import DOMPurify from 'dompurify';
import { h } from 'vue';

type Props = {
	html: string;
	tag?: string;
};

export function Sanitize({ html, tag = 'div' }: Props) {
	const clean = DOMPurify.sanitize(html);
	return h(tag, { innerHTML: clean });
}
