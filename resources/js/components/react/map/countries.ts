export interface CountryConfig {
	lat: number;
	lng: number;
	zoom: number;
	name: string;
	nameEs: string;
}

export const DEFAULT_COUNTRY = 'VE';

const COUNTRIES: Record<string, CountryConfig> = {
	VE: { lat: 8.0,   lng: -66.0, zoom: 6, name: 'Venezuela',  nameEs: 'Venezuela' },
	AR: { lat: -38.0, lng: -63.0, zoom: 4, name: 'Argentina',  nameEs: 'Argentina' },
	BO: { lat: -16.5, lng: -64.0, zoom: 6, name: 'Bolivia',    nameEs: 'Bolivia'   },
	BR: { lat: -14.0, lng: -51.0, zoom: 4, name: 'Brazil',     nameEs: 'Brasil'    },
	CL: { lat: -35.0, lng: -71.0, zoom: 5, name: 'Chile',      nameEs: 'Chile'     },
	CO: { lat: 4.0,   lng: -72.0, zoom: 5, name: 'Colombia',   nameEs: 'Colombia'  },
	CR: { lat: 9.8,   lng: -84.0, zoom: 7, name: 'Costa Rica', nameEs: 'Costa Rica'},
	CU: { lat: 22.0,  lng: -80.0, zoom: 7, name: 'Cuba',       nameEs: 'Cuba'      },
	DO: { lat: 18.7,  lng: -70.2, zoom: 7, name: 'Dominican R',nameEs: 'R. Dominicana'},
	EC: { lat: -1.8,  lng: -78.0, zoom: 7, name: 'Ecuador',    nameEs: 'Ecuador'   },
	SV: { lat: 13.8,  lng: -88.9, zoom: 8, name: 'El Salvador',nameEs: 'El Salvador'},
	GT: { lat: 15.5,  lng: -90.3, zoom: 7, name: 'Guatemala',  nameEs: 'Guatemala' },
	HN: { lat: 15.2,  lng: -86.2, zoom: 7, name: 'Honduras',   nameEs: 'Honduras'  },
	JM: { lat: 18.1,  lng: -77.3, zoom: 8, name: 'Jamaica',    nameEs: 'Jamaica'   },
	MX: { lat: 23.0,  lng: -102.0,zoom: 5, name: 'Mexico',     nameEs: 'México'    },
	NI: { lat: 12.9,  lng: -85.2, zoom: 7, name: 'Nicaragua',  nameEs: 'Nicaragua' },
	PA: { lat: 8.5,   lng: -80.0, zoom: 7, name: 'Panama',     nameEs: 'Panamá'    },
	PY: { lat: -23.0, lng: -58.0, zoom: 6, name: 'Paraguay',   nameEs: 'Paraguay'  },
	PE: { lat: -9.0,  lng: -75.0, zoom: 5, name: 'Peru',       nameEs: 'Perú'      },
	PR: { lat: 18.2,  lng: -66.5, zoom: 8, name: 'Puerto Rico',nameEs: 'Puerto Rico'},
	UY: { lat: -32.5, lng: -55.8, zoom: 7, name: 'Uruguay',    nameEs: 'Uruguay'   },
	US: { lat: 38.0,  lng: -97.0, zoom: 4, name: 'United States',nameEs:'Estados Unidos'},
	CA: { lat: 56.0,  lng: -106.0,zoom: 3, name: 'Canada',     nameEs: 'Canadá'    },
	ES: { lat: 40.0,  lng: -3.7,  zoom: 6, name: 'Spain',      nameEs: 'España'    },
	PT: { lat: 39.4,  lng: -8.2,  zoom: 6, name: 'Portugal',   nameEs: 'Portugal'  },
	DE: { lat: 51.1,  lng: 10.4,  zoom: 6, name: 'Germany',    nameEs: 'Alemania'  },
	FR: { lat: 46.2,  lng: 2.2,   zoom: 6, name: 'France',     nameEs: 'Francia'   },
	IT: { lat: 41.8,  lng: 12.5,  zoom: 6, name: 'Italy',      nameEs: 'Italia'    },
	GB: { lat: 55.3,  lng: -3.4,  zoom: 5, name: 'United Kingdom',nameEs: 'Reino Unido'},
	CN: { lat: 35.8,  lng: 104.1, zoom: 4, name: 'China',      nameEs: 'China'     },
	JP: { lat: 36.2,  lng: 138.2, zoom: 5, name: 'Japan',      nameEs: 'Japón'     },
	IN: { lat: 20.5,  lng: 78.9,  zoom: 5, name: 'India',      nameEs: 'India'     },
	AU: { lat: -25.2, lng: 133.7, zoom: 4, name: 'Australia',  nameEs: 'Australia' },
};

export function getCountry(code: string): CountryConfig {
	return COUNTRIES[code.toUpperCase()] ?? COUNTRIES[DEFAULT_COUNTRY];
}
