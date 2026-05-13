import { useState, useEffect, useRef } from 'react';

interface SearchResult {
	place_id: number;
	display_name: string;
	lat: string;
	lon: string;
}

interface AddressSearchProps {
	onSelect: (lat: number, lng: number) => void;
}

export function AddressSearch({ onSelect }: AddressSearchProps) {
	const [query, setQuery] = useState('');
	const [results, setResults] = useState<SearchResult[]>([]);
	const [isOpen, setIsOpen] = useState(false);
	const [loading, setLoading] = useState(false);
	const containerRef = useRef<HTMLDivElement>(null);

	useEffect(() => {
		const handleClickOutside = (event: MouseEvent) => {
			if (containerRef.current && !containerRef.current.contains(event.target as Node)) {
				setIsOpen(false);
			}
		};
		document.addEventListener('mousedown', handleClickOutside);
		return () => document.removeEventListener('mousedown', handleClickOutside);
	}, []);

	useEffect(() => {
		if (query.length < 3) {
			setResults([]);
			return;
		}

		const timer = setTimeout(async () => {
			setLoading(true);
			try {
				const response = await fetch(
					`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&countrycodes=ve&limit=5`
				);
				const data = await response.json();
				setResults(data);
				setIsOpen(true);
			} catch (error) {
				console.error('Search error:', error);
			} finally {
				setLoading(false);
			}
		}, 600);

		return () => clearTimeout(timer);
	}, [query]);

	return (
		<div className="relative mb-4" ref={containerRef}>
			<label className="block text-sm font-bold text-zinc-700 mb-2">Buscar dirección</label>
			<div className="relative!">
				<div className="absolute! inset-y-0! left-0! pl-4! flex! items-center! pointer-events-none!">
					<svg className={`size-5 ${loading ? 'animate-spin text-brand-600' : 'text-zinc-500'}`} viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5">
						{loading ? (
							<path strokeLinecap="round" strokeLinejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
						) : (
							<path strokeLinecap="round" strokeLinejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
						)}
					</svg>
				</div>
				<input
					type="text"
					value={query}
					onChange={(e) => setQuery(e.target.value)}
					placeholder="Ej: F5W4+XP5, Caracas 1060, Miranda, Venezuela"
					className="block! w-full! pl-12! pr-3! py-3! border! border-zinc-200! rounded-2xl! text-sm! placeholder-zinc-500! focus:outline-none! focus:ring-2! focus:ring-brand-500/20! focus:border-brand-500! transition-all! bg-white! shadow-sm!"
				/>
			</div>

			{isOpen && results.length > 0 && (
				<div className="absolute z-[10000] w-full mt-2 bg-white border border-zinc-100 rounded-2xl shadow-xl overflow-hidden animate-in fade-in slide-in-from-top-1 duration-200">
					<ul className="py-1">
						{results.map((result) => (
							<li
								key={result.place_id}
								onClick={() => {
									onSelect(Number(result.lat), Number(result.lon));
									setQuery(result.display_name);
									setIsOpen(false);
								}}
								className="px-4 py-3 cursor-pointer transition-colors border-b last:border-0 border-zinc-50"
							>
								<div className="flex gap-3">
									<svg className="size-5 text-zinc-400 shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
										<path strokeLinecap="round" strokeLinejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
										<path strokeLinecap="round" strokeLinejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
									</svg>
									<span className="text-sm text-zinc-700 font-medium line-clamp-2 leading-relaxed">{result.display_name}</span>
								</div>
							</li>
						))}
					</ul>
				</div>
			)}
		</div>
	);
}
