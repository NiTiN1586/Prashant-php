<template>
	<div class="text-center">
		<h2 class="text-3xl font-semibold mb-3">{{ t('auth.sign-in.title') }}</h2>
		<p class="mb-10">{{ t('auth.sign-in.subtitle') }}</p>

		<button
			:disabled="disabled"
			class="px-5 py-3 rounded inline-flex max-w-full gap-5 border items-center justify-center hover:bg-gray-100"
			:class="{ 'grayscale opacity-50': disabled }"
			@click="onLogin"
		>
			<svg
				width="32"
				height="32"
				viewBox="0 0 32 32"
				fill="none"
				xmlns="http://www.w3.org/2000/svg"
				class="flex-shrink-0"
			>
				<path
					d="M1.7793 8.78431C6.19672 -0.305865 19.0349 -2.91992 26.7826 3.69112C27.1967 4.04528 27.2485 4.24766 26.8171 4.63555C25.5229 5.83296 24.2805 7.08096 23.0381 8.32896C22.7621 8.61567 22.5895 8.69999 22.2271 8.39642C17.7062 4.60182 10.1482 6.06907 7.42186 12.2753C7.33559 12.4777 7.24931 12.697 7.16303 12.8993C6.80066 13.0005 6.57634 12.7138 6.33476 12.5452C4.98883 11.4996 3.62564 10.4877 2.27971 9.44205C2.05539 9.2734 1.84832 9.08788 1.7793 8.78431Z"
					fill="#E94335"
				/>
				<path
					d="M7.16629 19.0552C8.40869 22.1752 10.5484 24.3677 13.896 25.2784C16.7604 26.0542 19.4695 25.5651 21.9888 24.081C22.7481 24.2496 23.214 24.8736 23.8007 25.2952C24.7497 25.9698 25.6643 26.6782 26.5788 27.4034C26.8204 27.5889 27.1137 27.7407 27.1137 28.1117C24.8705 30.2029 22.1441 31.2654 19.1417 31.7714C12.1014 32.9688 4.8713 29.3765 1.74805 23.1197C1.90335 22.6475 2.35199 22.4788 2.6971 22.1921C3.88774 21.2308 5.13014 20.337 6.33803 19.4094C6.5796 19.2239 6.78667 18.954 7.16629 19.0552Z"
					fill="#34A753"
				/>
				<path
					d="M27.1108 28.0942C25.6095 26.9305 24.1255 25.7837 22.6243 24.62C22.4 24.4513 22.1929 24.249 21.9858 24.0803C23.4526 22.9672 24.5397 21.6012 25.0401 19.8135C25.1954 19.2738 25.0919 19.1389 24.5224 19.1389C22.0549 19.1558 19.5873 19.1389 17.1198 19.1558C16.5158 19.1558 16.3433 19.004 16.3605 18.3968C16.395 16.7947 16.395 15.1925 16.3605 13.5903C16.3433 13.0675 16.5158 12.9326 17.0335 12.9326C21.7098 12.9495 26.4033 12.9495 31.0795 12.9326C31.4592 12.9326 31.7352 12.9495 31.8043 13.4217C32.4945 19.0208 31.4592 24.0634 27.1108 28.0942Z"
					fill="#4284F3"
				/>
				<path
					d="M7.16517 19.0559C5.37059 20.4051 3.55876 21.7543 1.76418 23.1035C-0.582573 18.3307 -0.599829 13.5579 1.79869 8.78516C3.59327 10.1512 5.37059 11.5173 7.16517 12.9002C6.50946 14.9408 6.50946 16.9984 7.16517 19.0559Z"
					fill="#FABB05"
				/>
			</svg>

			<span class="truncate">{{ t('auth.sign-in.googleButton') }}</span>
		</button>

		<div
			class="mt-3 text-sm text-gray-400"
			v-text="disabled ? 'Loading...' : '&nbsp;'"
		/>
	</div>
</template>

<script lang="ts">
import { defineComponent } from '@vue/runtime-core';
import { useI18n } from 'vue-i18n';
import { onMounted, ref } from 'vue';
import { useRoute } from 'vue-router';

export default defineComponent({
	setup() {
		const { t } = useI18n();
		const route = useRoute();
		const disabled = ref(false);

		async function onLogin() {
			disabled.value = true;
			const api = import.meta.env.VITE_API_REST;
			const url = new URL(
				`${api}/_user-provider/authentication/google/auth-url`,
				location.origin,
			);
			url.searchParams.append('postLoginRedirectUrl', location.origin);
			const response = await fetch(url.href);
			const json = await response.json();
			window.location.href = json.data.google_auth_url;
		}

		onMounted(() => {
			if (route.query.action === 'auto') onLogin();
		});

		return { t, onLogin, disabled };
	},
});
</script>
