const defaultTheme = require('tailwindcss/defaultTheme');
const plugin = require('tailwindcss/plugin');

/** @type {import('tailwindcss/tailwind-config').TailwindConfig} */
const tailwindConfig = {
	content: ['./index.html', './src/**/*.{vue,js,ts,jsx,tsx}'],
	theme: {
		// Options from Figma, but we keep Tailwind CSS Scale
		borderRadius: {
			none: '0px',
			// When changing DEFAULT, check where it is used
			DEFAULT: '0.75rem', // 12px Used for most of the elements
			lg: '0.5rem', // 8px Used for buttons
			full: '9999px',
		},
		// Options from Figma
		boxShadow: {
			DEFAULT: '0px 8px 20px rgba(70, 128, 255, 0.16)',
			none: 'none',
			mood: '10px 60px 80px rgba(43, 94, 224, 0.2)',
		},
		extend: {
			fontFamily: {
				// Font is added in index.html
				sans: ['Poppins', ...defaultTheme.fontFamily.sans],
			},
			colors: {
				'w-dark-blue': '#333984',
				'w-grey-icons': '#889DC6',
				'w-light-grey': '#C4D6F6',
				'w-charts-background': '#F1F6FF',
				'w-grey-background-light': '#F8F8FF',
				'w-purple': '#BB6BD9',
				'w-orange': '#F4A827',
				'w-light-orange': '#F2C94C',
				'w-vivid-green': '#2AD7B3',
				'w-gradient-button-1': '#6725F1',
				'w-gradient-button-2': '#BC87FF',
				'w-blue-deep': '#072EF5',
				'w-pink': '#FF005C',
				'w-tech-green': '#0DE4E4',
			},
		},
	},
	plugins: [
		require('@tailwindcss/forms'),
		require('@tailwindcss/typography'),
		plugin(function ({ addBase, theme }) {
			addBase({
				'body': { color: theme('colors.w-dark-blue') },
				'::-webkit-calendar-picker-indicator': {
					backgroundImage: theme('backgroundImage.none'),
					display: 'none',
				},
			});
		}),
	],
};

module.exports = tailwindConfig;
