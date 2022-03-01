import { h } from 'vue';
import { Meta, Story } from '@storybook/vue3';
import { getPicsumImage } from '../../../utils/get-picsum-image';
import Component from './avatar.component.vue';

export default {
	title: 'Components / Avatar',
	component: Component,
} as Meta;

export const Avatar: Story = () => {
	return (
		<div class="flex gap-3">
			<Component alt="image" src={getPicsumImage(0)} />
			<Component alt="image" src={getPicsumImage(1)} />
			<Component alt="image" src={getPicsumImage(2)} />
			<Component alt="image" src={getPicsumImage(3)} />
			<Component alt="image" src={getPicsumImage(4)} />
			<Component alt="image" src={getPicsumImage(5)} />
		</div>
	);
};
