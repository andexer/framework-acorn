import { useEffect, useMemo, useRef, useState } from 'react';
import { MapContainer, Marker, TileLayer, useMap, useMapEvents } from 'react-leaflet';
import L from 'leaflet';
import type { LatLngBoundsExpression } from 'leaflet';
import { AddressSearch } from './AddressSearch';

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

const DEFAULT_CENTER: [number, number] = [10.480600, -66.903600];

const MAP_BOUNDS: LatLngBoundsExpression = [
	[VENEZUELA_BOUNDS.minLat, VENEZUELA_BOUNDS.minLng],
	[VENEZUELA_BOUNDS.maxLat, VENEZUELA_BOUNDS.maxLng],
];

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

function AutoResizeMap() {
	const map = useMap();

	useEffect(() => {
		map.invalidateSize();

		const timers = [
			setTimeout(() => map.invalidateSize(), 50),
			setTimeout(() => map.invalidateSize(), 150),
			setTimeout(() => map.invalidateSize(), 300),
			setTimeout(() => map.invalidateSize(), 600),
			setTimeout(() => map.invalidateSize(), 1000),
		];

		return () => {
			timers.forEach(clearTimeout);
		};
	}, [map]);

	return null;
}

function FlyToPoint({ point }: { point: Point }) {
	const map = useMap();
	const isFirstRender = useRef(true);

	useEffect(() => {
		if (isFirstRender.current) {
			isFirstRender.current = false;
			return;
		}

		if (!point || !Number.isFinite(point.lat) || !Number.isFinite(point.lng)) {
			return;
		}

		const timer = setTimeout(() => {
			try {
				const currentZoom = map.getZoom();
				const zoom = typeof currentZoom === 'number' && Number.isFinite(currentZoom) ? Math.max(currentZoom, 9) : 15;

				const center = map.getCenter();
				if (center && Number.isFinite(center.lat) && Number.isFinite(center.lng)) {
					map.flyTo([point.lat, point.lng], zoom, { animate: true, duration: 0.8 });
				}
			} catch (error) {
				console.warn('[FlyToPoint] map.flyTo skipped or failed:', error);
			}
		}, 100);

		return () => clearTimeout(timer);
	}, [map, point.lat, point.lng]);

	return null;
}

function FullscreenControl() {
	const map = useMap();
	const [isFullscreen, setIsFullscreen] = useState(false);

	useEffect(() => {
		const handleFullscreenChange = () => {
			setIsFullscreen(!!document.fullscreenElement);
			setTimeout(() => map.invalidateSize(), 100);
		};

		document.addEventListener('fullscreenchange', handleFullscreenChange);
		return () => document.removeEventListener('fullscreenchange', handleFullscreenChange);
	}, [map]);

	const toggleFullscreen = () => {
		const container = map.getContainer().parentElement;
		if (!container) return;

		if (!document.fullscreenElement) {
			container.requestFullscreen().catch(() => { });
		} else {
			document.exitFullscreen();
		}
	};

	return (
		<div className="leaflet-top leaflet-right" style={{ marginTop: '10px', marginRight: '10px' }}>
			<div className="leaflet-control">
				<button
					type="button"
					onClick={toggleFullscreen}
					className="bg-orange-500! hover:bg-orange-600! text-white! w-10! h-10! min-w-10! min-h-10! max-w-10! max-h-10! rounded-xl! shadow-lg! border! border-zinc-200! flex! items-center! justify-center! transition-all! active:scale-95! group!"
					title={isFullscreen ? 'Salir de pantalla completa' : 'Pantalla completa'}
				>
					{isFullscreen ? (
						<svg className="w-5! h-5! min-w-5! min-h-5! max-w-5! max-h-5! transition-transform! group-hover:scale-110!" fill="none" viewBox="0 0 24 24" stroke="white" strokeWidth={3}>
							<path strokeLinecap="round" strokeLinejoin="round" d="M9 9V4.5M9 9H4.5M9 9L3.75 3.75M9 15v4.5M9 15H4.5M9 15l-5.25 5.25M15 9V4.5M15 9h4.5M15 9l5.25-5.25M15 15v4.5M15 15h4.5M15 15l5.25 5.25" />
						</svg>
					) : (
						<svg className="w-5! h-5! min-w-5! min-h-5! max-w-5! max-h-5! transition-transform! group-hover:scale-110!" fill="none" viewBox="0 0 24 24" stroke="white" strokeWidth={3}>
							<path strokeLinecap="round" strokeLinejoin="round" d="M3.75 3.75v4.5m0-4.5h4.5m-4.5 0L9 9M3.75 20.25v-4.5m0 4.5h4.5m-4.5 0L9 15M20.25 3.75h-4.5m4.5 0v4.5m0-4.5L15 9m5.25 11.25h-4.5m4.5 0v-4.5m0 4.5L15 15" />
						</svg>
					)}
				</button>
			</div>
		</div>
	);
}

export function MapaDireccionesPicker({ lat, lng, height = 460, onPick, onOutside }: PickerProps) {
	const validLat = typeof lat === 'number' && Number.isFinite(lat) ? lat : Number(lat);
	const validLng = typeof lng === 'number' && Number.isFinite(lng) ? lng : Number(lng);

	const initialLat = Number.isFinite(validLat) && insideVenezuela(validLat, validLng) ? validLat : DEFAULT_CENTER[0];
	const initialLng = Number.isFinite(validLng) && insideVenezuela(validLat, validLng) ? validLng : DEFAULT_CENTER[1];
	const [point, setPoint] = useState<Point>({ lat: initialLat, lng: initialLng });

	const showOutsideToast = () => {
		window.dispatchEvent(new CustomEvent('notify', {
			detail: {
				content: 'Esa dirección es inválida. No se permiten referencias externas al país.',
				type: 'error',
				duration: 5000
			}
		}));
	};

	const center = useMemo<[number, number]>(() => {
		if (Number.isFinite(validLat) && Number.isFinite(validLng) && insideVenezuela(validLat, validLng)) {
			return [validLat, validLng];
		}
		return DEFAULT_CENTER;
	}, [validLat, validLng]);

	useEffect(() => {
		if (Number.isFinite(validLat) && Number.isFinite(validLng) && insideVenezuela(validLat, validLng)) {
			setPoint({ lat: validLat, lng: validLng });
		}
	}, [validLat, validLng]);

	const handleSearchSelect = (newLat: number, newLng: number, isVenezuela: boolean) => {
		if (!isVenezuela || !insideVenezuela(newLat, newLng)) {
			showOutsideToast();
			onOutside(newLat, newLng);
			return;
		}
		setPoint({ lat: newLat, lng: newLng });
		onPick(newLat, newLng);
	};

	return (
		<div className="w-full">
			<AddressSearch onSelect={handleSearchSelect} />

			<div style={{ height, width: '100%', borderRadius: '24px', overflow: 'hidden', border: '1px solid #e5e7eb', boxShadow: '0 4px 6px -1px rgb(0 0 0 / 0.1)' }}>
				<MapContainer
					center={center}
					zoom={15}
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
					<AutoResizeMap />

					<FullscreenControl />
					<FlyToPoint point={point} />
					<ClickHandler onPick={onPick} onOutside={onOutside} setPoint={setPoint} />
					<Marker
						position={[point.lat, point.lng]}
						icon={accentIcon}
						draggable={true}
						eventHandlers={{
							dragend: (e) => {
								const marker = e.target;
								const position = marker.getLatLng();
								const lat = Number(position.lat.toFixed(6));
								const lng = Number(position.lng.toFixed(6));

								if (!insideVenezuela(lat, lng)) {
									// Reset position if outside
									marker.setLatLng([point.lat, point.lng]);
									showOutsideToast();
									onOutside(lat, lng);
									return;
								}

								setPoint({ lat, lng });
								onPick(lat, lng);
							},
						}}
					/>
				</MapContainer>
			</div>
		</div>
	);
}

export { insideVenezuela, VENEZUELA_BOUNDS };
