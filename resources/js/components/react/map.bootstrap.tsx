import React from 'react';
import { createRoot } from 'react-dom/client';
import 'leaflet/dist/leaflet.css';
import { MapComponent } from './map/MapComponent';
import { getCountry, DEFAULT_COUNTRY } from './map/countries';

export function mountFrameworkMapIslands() {
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

if (document.readyState === 'loading') {
	document.addEventListener('DOMContentLoaded', mountFrameworkMapIslands);
} else {
	mountFrameworkMapIslands();
}
