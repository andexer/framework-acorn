/**
 * map-app.tsx — Entry point de la isla React del mapa.
 *
 * Se monta sobre cualquier <div id="map-root"> que encuentre en el DOM.
 * Los datos del país se leen desde data-* attributes inyectados por PHP/Blade.
 */
import React from 'react';
import { createRoot } from 'react-dom/client';
import 'leaflet/dist/leaflet.css';
import { MapComponent } from './components/map/MapComponent';
import { getCountry, DEFAULT_COUNTRY } from './components/map/countries';

function mount() {
	const containers = document.querySelectorAll<HTMLDivElement>('[id^="map-root"]');

	containers.forEach((container) => {
		const code    = container.dataset.country ?? DEFAULT_COUNTRY;
		const height  = container.dataset.height ? parseInt(container.dataset.height, 10) : 480;
		const country = { ...getCountry(code), code };

		const root = createRoot(container);
		root.render(
			<React.StrictMode>
				<MapComponent country={country} height={height} />
			</React.StrictMode>,
		);
	});
}

// Espera a que el DOM esté listo (el script puede cargarse con defer)
if (document.readyState === 'loading') {
	document.addEventListener('DOMContentLoaded', mount);
} else {
	mount();
}
