<template>
	<TransitionRoot appear :show="modelValue" as="template">
		<Dialog
			as="div"
			class="fixed inset-0 z-10 overflow-y-auto"
			@close="closeModal()"
		>
			<div class="fixed inset-0 z-10 overflow-y-auto">
				<div class="min-h-screen px-4 text-center">
					<TransitionChild
						as="template"
						enter="duration-300 ease-out"
						enter-from="opacity-0"
						enter-to="opacity-100"
						leave="duration-200 ease-in"
						leave-from="opacity-100"
						leave-to="opacity-0"
					>
						<DialogOverlay class="fixed inset-0 bg-black opacity-25" />
					</TransitionChild>
					<!-- To align center dialog vertically -->
					<span class="inline-block text-red h-screen align-middle">
						&#8203;
					</span>
					<TransitionChild
						as="template"
						enter="duration-300 ease-out"
						enter-from="opacity-0 scale-95"
						enter-to="opacity-100 scale-100"
						leave="duration-200 ease-in"
						leave-from="opacity-100 scale-100"
						leave-to="opacity-0 scale-95"
					>
						<div
							class="inline-block w-full max-w-md p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-lg shadow"
							:style="{ '--tw-shadow': '0px 0px 50px rgba(43, 94, 224, 0.2)' }"
						>
							<DialogTitle
								as="h3"
								class="text-lg font-medium leading-6 text-w-dark-blue"
							>
								{{ title }}
								<button class="float-right" @click="closeModal">
									<CloseIcon class="h-6 w-6 inline text-w-dark-blue" />
								</button>
							</DialogTitle>
							<div class="mt-2">
								<slot name="body"></slot>
							</div>
							<div class="mt-4 text-right">
								<slot name="actions"></slot>
							</div>
						</div>
					</TransitionChild>
				</div>
			</div>
		</Dialog>
	</TransitionRoot>
</template>

<script lang="ts">
import { defineComponent } from '@vue/runtime-core';
import { PropType } from 'vue';
import {
	TransitionRoot,
	TransitionChild,
	Dialog,
	DialogOverlay,
	DialogTitle,
} from '@headlessui/vue';
import CloseIcon from '@witcher/icons/close.component.vue';

export default defineComponent({
	components: {
		TransitionRoot,
		TransitionChild,
		Dialog,
		DialogOverlay,
		DialogTitle,
		CloseIcon,
	},
	props: {
		title: {
			type: String as PropType<string>,
			required: true,
		},
		modelValue: {
			type: Boolean,
			required: true,
		},
	},
	emits: ['update:modelValue'],
	setup(_, { emit }) {
		return {
			closeModal() {
				emit('update:modelValue', false);
			},
		};
	},
});
</script>
