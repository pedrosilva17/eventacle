import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

const animate = require('tailwindcss-animate');

/** @type {import('tailwindcss').Config} */
export default {
	content: [
		'./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
		'./vendor/laravel/jetstream/**/*.blade.php',
		'./storage/framework/views/*.php',
		'./resources/views/**/*.blade.php',
		'./resources/js/**/*.vue',
	],

	theme: {
		container: {
			center: true,
			padding: '2rem',
			screens: {
				'2xl': '1400px',
			},
		},
		extend: {
			backgroundImage: ({ theme }) => ({
				'overflow-gradient': `linear-gradient(90deg, ${theme('colors.white.light')} 0%, rgba(0,0,0,0) 5%, rgba(0,0,0,0) 95%, ${theme('colors.white.light')} 100%);`,
				'overflow-gradient-dark': `linear-gradient(90deg, ${theme('colors.black.light')} 0%, rgba(0,0,0,0) 5%, rgba(0,0,0,0) 95%, ${theme('colors.black.light')} 100%);`,
			}),
			boxShadow: {
				reverse: 'inset 0 4px 8px 0 rgb(255 255 255 / 0.15)',
			},
			fontFamily: {
				sans: ['Inter', ...defaultTheme.fontFamily.sans],
				serif: ['Aleo', ...defaultTheme.fontFamily.serif],
			},
			gridTemplateColumns: {
				auto: 'auto 1fr',
			},
			colors: {
				primary: {
					DEFAULT: '#622E88',
					extralight: '#A681CD',
					light: '#8651AD',
					dark: '#4B1C68',
					extradark: '#3B0C58',
				},
				secondary: {
					DEFAULT: '#CB80AB',
					extralight: '#F1C7DF',
					light: '#E1B7CF',
					dark: '#BE6095',
					extradark: '#AE5085',
				},
				success: {
					DEFAULT: '#B1E85E',
					extralight: '#DBF0A4',
					light: '#CBE094',
					dark: '#97E029',
					extradark: '#87D019',
				},
				error: {
					DEFAULT: '#F26419',
					light: '#F58B51',
					dark: '#C14B0B',
				},
				white: {
					DEFAULT: '#EDEDED',
					light: '#FDFDFD',
					dark: '#DDDDDD',
				},
				black: {
					DEFAULT: '#222222',
					light: '#323232',
					dark: '#121212',
				},
			},
			animation: {
				shake: 'shake 0.5s ease-in-out',
				'accordion-down': 'accordion-down 0.2s ease-out',
				'accordion-up': 'accordion-up 0.2s ease-out',
			},
			keyframes: {
				shake: {
					'10%, 90%': {
						transform: 'translate(-1px, 0)',
					},
					'30%, 70%': {
						transform: 'translate(2px, 0)',
					},

					'50%': {
						transform: 'translate(-3px, 0)',
					},
				},
				'accordion-down': {
					from: { height: 0 },
					to: { height: 'var(--radix-accordion-content-height)' },
				},
				'accordion-up': {
					from: { height: 'var(--radix-accordion-content-height)' },
					to: { height: 0 },
				},
			},
		},
	},

	plugins: [animate, forms, typography],
};
