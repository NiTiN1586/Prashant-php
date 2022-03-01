<template>
	<section class="relative overflow-hidden">
		<Background
			class="absolute min-h-screen background right-full sm:right-[calc(100%-70px)] md:right-3/4 lg:right-[40%]"
		/>
		<Container class="z-10 relative" fluid>
			<Row class="min-h-screen">
				<Column
					class="hidden md:flex items-center justify-center md:w-1/4 lg:w-3/5"
				>
					<Row class="hidden lg:flex justify-center mb-30 max-w-7xl mx-auto">
						<Column class="w-10/12 xl:w-2/3">
							<HrIcon />
							<Translation
								keypath="auth.index.quote"
								scope="global"
								tag="h1"
								class="text-4xl text-white font-light mb-10 mt-5"
							>
								<strong class="font-bold">
									{{ t('auth.index.quote.0') }}
								</strong>
							</Translation>
							<PmIcon />
						</Column>
					</Row>
				</Column>
				<Column class="w-full md:w-3/4 lg:w-2/5">
					<Row class="justify-center h-screen">
						<Column class="w-full flex flex-col justify-between">
							<PublicLogo class="mt-16 mb-8 mx-auto" />
							<div class="mb-16">
								<RouterView />
							</div>
							<div class="mb-16 text-gray-400 text-sm text-center">
								{{ t('auth.index.rights') }}
							</div>
						</Column>
					</Row>
				</Column>
			</Row>
		</Container>
	</section>
</template>

<script lang="ts">
import { defineComponent } from '@vue/runtime-core';
import { RouterView } from 'vue-router';
import { Translation, useI18n } from 'vue-i18n';
import Container from '../components/grid/container.component.vue';
import Row from '../components/grid/row.component.vue';
import Column from '../components/grid/column.component.vue';
import PublicLogo from './components/public-logo.component.vue';
import Background from './components/background.component.vue';
import HrIcon from './components/hr.component.vue';
import PmIcon from './components/pm.component.vue';

export default defineComponent({
	components: {
		Translation,
		PublicLogo,
		Container,
		Row,
		Column,
		RouterView,
		Background,
		HrIcon,
		PmIcon,
	},
	setup() {
		const { t } = useI18n();
		return { t };
	},
});
</script>
