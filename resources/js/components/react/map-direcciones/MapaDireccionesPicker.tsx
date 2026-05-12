import { useEffect, useMemo, useState } from 'react';
import { MapContainer, Marker, TileLayer, useMap, useMapEvents } from 'react-leaflet';
import L from 'leaflet';
import type { LatLngBoundsExpression } from 'leaflet';

type Point = {
	lat: number;
	lng: number;
};

type PickerProps = {
	lat: number;
	lng: number;
	height?: number;
	onPick: (lat: number, lng: number) => void;
	onOutside: (lat: number, lng: number) => void;
};

const VENEZUELA_BOUNDS = {
	minLat: 0.4,
	maxLat: 12.4,
	minLng: -73.6,
	maxLng: -59.6,
};

const DEFAULT_CENTER: [number, number] = [8.5, -66.5];

const MAP_BOUNDS: LatLngBoundsExpression = [
	[VENEZUELA_BOUNDS.minLat, VENEZUELA_BOUNDS.minLng],
	[VENEZUELA_BOUNDS.maxLat, VENEZUELA_BOUNDS.maxLng],
];

delete (L.Icon.Default.prototype as unknown as Record<string, unknown>)._getIconUrl;
L.Icon.Default.mergeOptions({
	iconRetinaUrl: 'https://unpkg.com/leaflet@latest/dist/images/marker-icon-2x.png',
	iconUrl: 'https://unpkg.com/leaflet@latest/dist/images/marker-icon.png',
	shadowUrl: 'https://unpkg.com/leaflet@latest/dist/images/marker-shadow.png',
});

function insideVenezuela(lat: number, lng: number): boolean {
	return (
		lat >= VENEZUELA_BOUNDS.minLat &&
		lat <= VENEZUELA_BOUNDS.maxLat &&
		lng >= VENEZUELA_BOUNDS.minLng &&
		lng <= VENEZUELA_BOUNDS.maxLng
	);
}

function ClickHandler({
	onPick,
	onOutside,
	setPoint,
}: {
	onPick: PickerProps['onPick'];
	onOutside: PickerProps['onOutside'];
	setPoint: (point: Point) => void;
}) {
	useMapEvents({
		click(event) {
			const lat = Number(event.latlng.lat.toFixed(6));
			const lng = Number(event.latlng.lng.toFixed(6));

			if (!insideVenezuela(lat, lng)) {
				onOutside(lat, lng);
				return;
			}

			setPoint({ lat, lng });
			onPick(lat, lng);
		},
	});

	return null;
}

function FlyToPoint({ point }: { point: Point }) {
	const map = useMap();

	useEffect(() => {
		map.flyTo([point.lat, point.lng], Math.max(map.getZoom(), 9), { animate: true, duration: 0.8 });
	}, [map, point.lat, point.lng]);

	return null;
}

export function MapaDireccionesPicker({ lat, lng, height = 460, onPick, onOutside }: PickerProps) {
	const [point, setPoint] = useState<Point>({ lat, lng });
	const center = useMemo<[number, number]>(() => {
		if (insideVenezuela(lat, lng)) {
			return [lat, lng];
		}
		return DEFAULT_CENTER;
	}, [lat, lng]);

	useEffect(() => {
		if (insideVenezuela(lat, lng)) {
			setPoint({ lat, lng });
		}
	}, [lat, lng]);

	return (
		<div style={{ height, width: '100%', borderRadius: '14px', overflow: 'hidden' }}>
			<MapContainer
				center={center}
				zoom={6}
				minZoom={5}
				maxZoom={18}
				scrollWheelZoom={true}
				maxBounds={MAP_BOUNDS}
				maxBoundsViscosity={0.8}
				style={{ height: '100%', width: '100%' }}
			>
				<TileLayer
					url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"
					attribution='&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
					maxZoom={19}
				/>

				<FlyToPoint point={point} />
				<ClickHandler onPick={onPick} onOutside={onOutside} setPoint={setPoint} />
				<Marker position={[point.lat, point.lng]} />
			</MapContainer>
		</div>
	);
}

export { insideVenezuela, VENEZUELA_BOUNDS };
