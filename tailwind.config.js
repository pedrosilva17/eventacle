import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

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
		extend: {
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
				shake: 'shake 1s ease-in-out',
			},
			keyframes: {
				shake: {
					'10%, 90%': {
						transform: 'translate(-1px, 0)',
					},
					'20%, 80%': {
						transform: 'translate(2px, 0)',
					},

					'30%, 50%, 70%': {
						transform: 'translate(-3px, 0)',
					},

					'40%, 60%': {
						transform: 'translate(3px, 0)',
					},
				},
			},
		},
	},

	plugins: [forms, typography],
};
