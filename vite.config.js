import { defineConfig } from 'vite'
import tailwindcss from '@tailwindcss/vite';
import laravel from 'laravel-vite-plugin'

if (!process.env.APP_URL) {
	process.env.APP_URL = 'https://wordpress.ddev.site';
}

export default defineConfig({
	base: '/wp-content/plugins/framework/public/build/',
	plugins: [
		tailwindcss(),
		laravel({
			input: [
				'resources/css/app.css',
				'resources/js/app.js',
			],
			refresh: true,
		}),
	],
	resolve: {
		alias: {
			'@scripts': '/resources/js',
			'@styles': '/resources/css',
		},
	},
})
