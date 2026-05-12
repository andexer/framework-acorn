/**
 * global.d.ts — Declaraciones de módulos globales para TypeScript strict.
 * Permite importar archivos CSS sin error de tipo (Vite los maneja en runtime).
 */

// CSS modules / side-effect CSS imports
declare module '*.css' {
	const content: Record<string, string>;
	export default content;
}

// Imágenes y assets estáticos
declare module '*.png'  { const src: string; export default src; }
declare module '*.jpg'  { const src: string; export default src; }
declare module '*.jpeg' { const src: string; export default src; }
declare module '*.svg'  { const src: string; export default src; }
declare module '*.webp' { const src: string; export default src; }
