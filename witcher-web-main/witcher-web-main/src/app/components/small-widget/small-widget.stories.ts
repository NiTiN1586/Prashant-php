import { Meta, Story } from '@storybook/vue3';
import { defineComponent } from '@vue/runtime-core';
import Component from './small-widget.component.vue';

export default {
	title: 'Components / Small Widget',
	component: Component,
} as Meta;

export const SmallWidget: Story = () => {
	return defineComponent({
		components: { SmallWidget: Component },
		template: `
      <div class='grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3'>
      <SmallWidget title='260' subtitle='Total SP points consumed'>
        <svg width='48' height='48' viewBox='0 0 48 48' fill='none' xmlns='http://www.w3.org/2000/svg'>
          <rect width='48' height='48' rx='24' fill='#FF005C' fill-opacity='0.05' />
          <path
            d='M31.2 14.666L26.4 19.6438H30V28.3549C30 29.7238 28.92 30.8438 27.6 30.8438C26.28 30.8438 25.2 29.7238 25.2 28.3549V19.6438C25.2 16.8936 23.052 14.666 20.4 14.666C17.748 14.666 15.6 16.8936 15.6 19.6438V28.3549H12L16.8 33.3327L21.6 28.3549H18V19.6438C18 18.2749 19.08 17.1549 20.4 17.1549C21.72 17.1549 22.8 18.2749 22.8 19.6438V28.3549C22.8 31.1051 24.948 33.3327 27.6 33.3327C30.252 33.3327 32.4 31.1051 32.4 28.3549V19.6438H36L31.2 14.666Z'
            fill='#FF005C'
          />
        </svg>
      </SmallWidget>
      <SmallWidget title='550:23' subtitle='Total Hours Consumed'>
        <svg width='48' height='48' viewBox='0 0 48 48' fill='none' xmlns='http://www.w3.org/2000/svg'>
          <rect width='48' height='48' rx='24' fill='#072EF5' fill-opacity='0.05' />
          <path
            d='M23.3483 13.695C23.5234 13.5443 23.7513 13.4531 24.0005 13.4531C29.9906 13.4531 34.8466 18.3091 34.8466 24.2993C34.8466 30.2895 29.9906 35.1455 24.0005 35.1455C18.0103 35.1455 13.1543 30.2895 13.1543 24.2993C13.1543 23.747 13.602 23.2993 14.1543 23.2993C14.7066 23.2993 15.1543 23.747 15.1543 24.2993C15.1543 29.1849 19.1149 33.1455 24.0005 33.1455C28.8861 33.1455 32.8466 29.1849 32.8466 24.2993C32.8466 19.6097 29.1975 15.7724 24.5838 15.4721L24.5838 24.5557C24.5838 25.108 24.1361 25.5557 23.5838 25.5557C23.0315 25.5557 22.5838 25.108 22.5838 24.5557L22.5838 14.6671C22.5838 14.1959 22.9097 13.8009 23.3483 13.695Z'
            fill='#333984'
          />
        </svg>
      </SmallWidget>
      <SmallWidget title='32 / 123' subtitle='Total tasks completed'>
        <svg width='48' height='48' viewBox='0 0 48 48' fill='none' xmlns='http://www.w3.org/2000/svg'>
          <rect width='48' height='48' rx='24' fill='#072EF5' fill-opacity='0.05' />
          <path
            fill-rule='evenodd' clip-rule='evenodd'
            d='M20.1898 33.3327L10.666 24.3755L13.3517 21.8496L20.1898 28.2629L34.647 14.666L37.3327 17.2098L20.1898 33.3327Z'
            fill='#2AD7B3'
          />
        </svg>
      </SmallWidget>
      </div>
    `,
	});
};
