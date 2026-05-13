import { useEffect } from 'react';
import { MapContainer, TileLayer, Marker, Popup, useMap } from 'react-leaflet';
import L from 'leaflet';
import type { CountryConfig } from './countries';

delete (L.Icon.Default.prototype as unknown as Record<string, unknown>)._getIconUrl;
L.Icon.Default.mergeOptions({
	iconRetinaUrl: 'https://unpkg.com/leaflet@latest/dist/images/marker-icon-2x.png',
	iconUrl: 'https://unpkg.com/leaflet@latest/dist/images/marker-icon.png',
	shadowUrl: 'https://unpkg.com/leaflet@latest/dist/images/marker-shadow.png',
});

const accentIcon = new L.Icon({
	iconUrl: `data:image/svg+xml;utf8,${encodeURIComponent(`
		<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 36" width="24" height="36">
			<path d="M12 0C5.373 0 0 5.373 0 12c0 9 12 24 12 24s12-15 12-24C24 5.373 18.627 0 12 0z"
				fill="#f97316" stroke="#fff" stroke-width="1.5"/>
			<circle cx="12" cy="12" r="5" fill="#fff"/>
		</svg>
	`)}`,
	iconSize: [24, 36],
	iconAnchor: [12, 36],
	popupAnchor: [0, -36],
});

function FlyTo({ lat, lng, zoom }: { lat: number; lng: number; zoom: number }): null {
	const map = useMap();
	useEffect(() => {
		map.flyTo([lat, lng], zoom, { animate: true, duration: 1.2 });
	}, [lat, lng, zoom, map]);
	return null;
}

export interface MapComponentProps {
	country: CountryConfig & { code: string };
	height?: number;
}

export function MapComponent({ country, height = 480 }: MapComponentProps) {
	const { lat, lng, zoom, nameEs, name } = country;

	return (
		<div style={{ height, width: '100%', borderRadius: '16px', overflow: 'hidden', boxShadow: '0 4px 24px rgba(0,0,0,.12)' }}>
			<MapContainer
				center={[lat, lng]}
				zoom={zoom}
				scrollWheelZoom={true}
				style={{ height: '100%', width: '100%' }}
				attributionControl={true}
			>
				<TileLayer
					url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"
					attribution='&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
					maxZoom={19}
				/>
				<FlyTo lat={lat} lng={lng} zoom={zoom} />
				<Marker position={[lat, lng]} icon={accentIcon}>
					<Popup>
						<strong style={{ fontSize: '1rem' }}>{nameEs}</strong>
						<br />
						<span style={{ color: '#6b7280', fontSize: '.85rem' }}>{name}</span>
					</Popup>
				</Marker>
			</MapContainer>
		</div>
	);
}
