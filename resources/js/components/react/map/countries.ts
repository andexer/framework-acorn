/**
 * Diccionario de países con código ISO 3166-1 alpha-2.
 * Venezuela es el país por defecto del sistema.
 */
export interface CountryConfig {
	name: string;
	/** Nombre en español */
	nameEs: string;
	/** Latitud del centro geográfico */
	lat: number;
	/** Longitud del centro geográfico */
	lng: number;
	/** Zoom inicial de Leaflet */
	zoom: number;
}

export const COUNTRIES: Record<string, CountryConfig> = {
	// ── Venezuela (DEFAULT) ───────────────────────────────────────────
	VE: { name: 'Venezuela',         nameEs: 'Venezuela',         lat:   8.0,   lng:  -66.0,  zoom: 5 },

	// ── Latinoamérica ─────────────────────────────────────────────────
	AR: { name: 'Argentina',         nameEs: 'Argentina',         lat: -38.0,   lng:  -65.0,  zoom: 4 },
	BO: { name: 'Bolivia',           nameEs: 'Bolivia',           lat: -17.0,   lng:  -64.5,  zoom: 5 },
	BR: { name: 'Brazil',            nameEs: 'Brasil',            lat: -14.235, lng:  -51.925, zoom: 4 },
	CL: { name: 'Chile',             nameEs: 'Chile',             lat: -35.675, lng:  -71.543, zoom: 4 },
	CO: { name: 'Colombia',          nameEs: 'Colombia',          lat:   4.571, lng:  -74.297, zoom: 5 },
	CR: { name: 'Costa Rica',        nameEs: 'Costa Rica',        lat:   9.748, lng:  -83.753, zoom: 7 },
	CU: { name: 'Cuba',              nameEs: 'Cuba',              lat:  22.0,   lng:  -79.5,   zoom: 6 },
	DO: { name: 'Dominican Republic',nameEs: 'República Dominicana', lat: 18.735, lng: -70.163, zoom: 7 },
	EC: { name: 'Ecuador',           nameEs: 'Ecuador',           lat:  -1.831, lng:  -78.183, zoom: 6 },
	SV: { name: 'El Salvador',       nameEs: 'El Salvador',       lat:  13.794, lng:  -88.896, zoom: 8 },
	GT: { name: 'Guatemala',         nameEs: 'Guatemala',         lat:  15.784, lng:  -90.231, zoom: 7 },
	HN: { name: 'Honduras',          nameEs: 'Honduras',          lat:  15.2,   lng:  -86.242, zoom: 7 },
	JM: { name: 'Jamaica',           nameEs: 'Jamaica',           lat:  18.109, lng:  -77.297, zoom: 9 },
	MX: { name: 'Mexico',            nameEs: 'México',            lat:  23.634, lng: -102.552, zoom: 5 },
	NI: { name: 'Nicaragua',         nameEs: 'Nicaragua',         lat:  12.865, lng:  -85.207, zoom: 7 },
	PA: { name: 'Panama',            nameEs: 'Panamá',            lat:   8.538, lng:  -80.782, zoom: 7 },
	PY: { name: 'Paraguay',          nameEs: 'Paraguay',          lat: -23.443, lng:  -58.444, zoom: 6 },
	PE: { name: 'Peru',              nameEs: 'Perú',              lat:  -9.19,  lng:  -75.015, zoom: 5 },
	PR: { name: 'Puerto Rico',       nameEs: 'Puerto Rico',       lat:  18.221, lng:  -66.59,  zoom: 9 },
	UY: { name: 'Uruguay',           nameEs: 'Uruguay',           lat: -32.523, lng:  -55.766, zoom: 7 },

	// ── Resto del mundo ───────────────────────────────────────────────
	US: { name: 'United States',     nameEs: 'Estados Unidos',    lat:  37.09,  lng:  -95.713, zoom: 4 },
	CA: { name: 'Canada',            nameEs: 'Canadá',            lat:  56.13,  lng:  -106.347, zoom: 4 },
	ES: { name: 'Spain',             nameEs: 'España',            lat:  40.463, lng:   -3.749,  zoom: 5 },
	PT: { name: 'Portugal',          nameEs: 'Portugal',          lat:  39.399, lng:   -8.225,  zoom: 6 },
	DE: { name: 'Germany',           nameEs: 'Alemania',          lat:  51.165, lng:   10.452,  zoom: 5 },
	FR: { name: 'France',            nameEs: 'Francia',           lat:  46.227, lng:    2.213,  zoom: 5 },
	IT: { name: 'Italy',             nameEs: 'Italia',            lat:  41.872, lng:   12.568,  zoom: 5 },
	GB: { name: 'United Kingdom',    nameEs: 'Reino Unido',       lat:  55.378, lng:   -3.436,  zoom: 5 },
	CN: { name: 'China',             nameEs: 'China',             lat:  35.861, lng:  104.195,  zoom: 4 },
	JP: { name: 'Japan',             nameEs: 'Japón',             lat:  36.205, lng:  138.252,  zoom: 5 },
	IN: { name: 'India',             nameEs: 'India',             lat:  20.594, lng:   78.963,  zoom: 4 },
	AU: { name: 'Australia',         nameEs: 'Australia',         lat: -25.274, lng:  133.776,  zoom: 4 },
} as const;

export type CountryCode = keyof typeof COUNTRIES;

export const DEFAULT_COUNTRY: CountryCode = 'VE';

export function getCountry(code: string): CountryConfig {
	return COUNTRIES[code as CountryCode] ?? COUNTRIES[DEFAULT_COUNTRY];
}
