import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'
import tailwindcss from '@tailwindcss/vite';
import laravel from 'laravel-vite-plugin'
import path from 'path'

if (!process.env.APP_URL) {
	process.env.APP_URL = 'https://wordpress.ddev.site';
}

export default defineConfig({
	base: '/wp-content/plugins/framework-acorn/public/build/',
	plugins: [
		react(),
		tailwindcss(),
		laravel({
			input: [
				'resources/css/app.css',
				'resources/js/app.js',
				'resources/js/components/react/islands.bootstrap.tsx',
			],
			refresh: true,
		}),
	],
	resolve: {
		alias: {
			'@': path.resolve(__dirname, 'resources/js'),
			'@scripts': path.resolve(__dirname, 'resources/js'),
			'@styles': path.resolve(__dirname, 'resources/css'),
		},
	},
})
