/**
 * Search
 *
 * Code for searching for dex information, used by the Dex and
 * Teambuilder.
 *
 * Dependencies: battledata, search-index
 * Optional dependencies: pokedex, moves, items, abilities
 *
 * @author Guangcong Luo <guangcongluo@gmail.com>
 * @license MIT
 */

type SearchType = (
	'pokemon' | 'type' | 'tier' | 'move' | 'item' | 'ability' | 'egggroup' | 'category' | 'article'
);

type SearchRow = (
	[SearchType, ID, number?, number?] | ['sortpokemon' | 'sortmove', ''] | ['header' | 'html', string]
);

type SearchFilter = [string, string];

/** ID, SearchType, index (if alias), offset (if offset alias) */
declare const BattleSearchIndex: [ID, SearchType, number?, number?][];
declare const BattleSearchIndexOffset: any;
declare const BattleTeambuilderTable: any;

/**
 * Backend for search UIs.
 */

class DexSearch {
	query = '';

	/**
	 * Dex for the mod/generation to search.
	 */
	dex: ModdedDex = Dex;

	typedSearch: BattleTypedSearch<SearchType> | null = null;

	results: SearchRow[] | null = null;
	exactMatch = false;

	static typeTable = {
		pokemon: 1,
		type: 2,
		tier: 3,
		move: 4,
		item: 5,
		ability: 6,
		egggroup: 7,
		category: 8,
		article: 9,
	};
	static typeName = {
		pokemon: 'Pok&eacute;mon',
		type: 'Type',
		tier: 'Tiers',
		move: 'Moves',
		item: 'Items',
		ability: 'Abilities',
		egggroup: 'Egg group',
		category: 'Category',
		article: 'Article',
	};
	firstPokemonColumn: 'Tier' | 'Number' = 'Number';

	/**
	 * Column to sort by. Default is `null`, a smart sort determined by how good
	 * things are according to the base filters, falling back to dex number (for
	 * Pokemon) and name (for everything else).
	 */
	sortCol: string | null = null;

	/**
	 * Filters for the search result. Does not include the two base filters
	 * (format and species).
	 */
	filters: SearchFilter[] | null = null;

	constructor(searchType: SearchType | '' = '', formatid = '' as ID, species = '' as ID) {
		this.setType(searchType, formatid, species);
		if (window.room.curTeam.mod) this.dex = Dex.mod(window.room.curTeam.mod);
	}

	getTypedSearch(searchType: SearchType | '', format = '' as ID, speciesOrSet: ID | PokemonSet = '' as ID) {
		if (!searchType) return null;
		switch (searchType) {
		case 'pokemon': return new BattlePokemonSearch('pokemon', format, speciesOrSet);
		case 'item': return new BattleItemSearch('item', format, speciesOrSet);
		case 'move': return new BattleMoveSearch('move', format, speciesOrSet);
		case 'ability': return new BattleAbilitySearch('ability', format, speciesOrSet);
		case 'type': return new BattleTypeSearch('type', format, speciesOrSet);
		case 'category': return new BattleCategorySearch('category', format, speciesOrSet);
		}
		return null;
	}

	find(query: string) {
		query = toID(query);
		if (this.query === query && this.results) {
			return false;
		}
		this.query = query;
		if (!query) {
			this.results = this.typedSearch?.getResults(this.filters, this.sortCol) || [];
		} else {
			this.results = this.textSearch(query);
		}
		return true;
	}

	setType(searchType: SearchType | '', format = '' as ID, speciesOrSet: ID | PokemonSet = '' as ID) {
		// invalidate caches
		this.results = null;

		if (searchType !== this.typedSearch?.searchType) {
			this.filters = null;
			this.sortCol = null;
		}
		this.typedSearch = this.getTypedSearch(searchType, format, speciesOrSet);
		if (this.typedSearch) this.dex = this.typedSearch.dex;
	}

	addFilter(entry: SearchFilter): boolean {
		if (!this.typedSearch) return false;
		let [type] = entry;
		if (this.typedSearch.searchType === 'pokemon') {
			if (type === this.sortCol) this.sortCol = null;
			if (!['type', 'move', 'ability', 'egggroup', 'tier'].includes(type)) return false;
			if (type === 'move') entry[1] = toID(entry[1]);
			if (!this.filters) this.filters = [];
			this.results = null;
			for (const filter of this.filters) {
				if (filter[0] === type && filter[1] === entry[1]) {
					return true;
				}
			}
			this.filters.push(entry);
			return true;
		} else if (this.typedSearch.searchType === 'move') {
			if (type === this.sortCol) this.sortCol = null;
			if (!['type', 'category', 'pokemon'].includes(type)) return false;
			if (type === 'pokemon') entry[1] = toID(entry[1]);
			if (!this.filters) this.filters = [];
			this.filters.push(entry);
			this.results = null;
			return true;
		}
		return false;
	}

	removeFilter(entry?: SearchFilter): boolean {
		if (!this.filters) return false;
		if (entry) {
			const filterid = entry.join(':');
			let deleted: string[] | null = null;
			// delete specific filter
			for (let i = 0; i < this.filters.length; i++) {
				if (filterid === this.filters[i].join(':')) {
					deleted = this.filters[i];
					this.filters.splice(i, 1);
					break;
				}
			}
			if (!deleted) return false;
		} else {
			this.filters.pop();
		}
		if (!this.filters.length) this.filters = null;
		this.results = null;
		return true;
	}

	toggleSort(sortCol: string) {
		if (this.sortCol === sortCol) {
			this.sortCol = null;
		} else {
			this.sortCol = sortCol;
		}
		this.results = null;
	}

	filterLabel(filterType: string) {
		if (this.typedSearch && this.typedSearch.searchType !== filterType) {
			return 'Filter';
		}
		return null;
	}
	illegalLabel(id: ID) {
		return this.typedSearch?.illegalReasons?.[id] || null;
	}

	getTier(species: Species) {
		return this.typedSearch?.getTier(species) || '';
	}

	textSearch(query: string): SearchRow[] {
		query = toID(query);

		this.exactMatch = false;
		let searchType: SearchType | '' = this.typedSearch?.searchType || '';

		// If searchType exists, we're searching mainly for results of that type.
		// We'll still search for results of other types, but those results
		// will only be used to filter results for that type.
		let searchTypeIndex = (searchType ? DexSearch.typeTable[searchType] : -1);

		/** searching for "Psychic type" will make the type come up over the move */
		let qFilterType: 'type' | '' = '';
		if (query.slice(-4) === 'type') {
			if ((query.charAt(0).toUpperCase() + query.slice(1, -4)) in window.BattleTypeChart) {
				query = query.slice(0, -4);
				qFilterType = 'type';
			}
		}

		// i represents the location of the search index we're looking at
		let i = DexSearch.getClosest(query);
		this.exactMatch = (BattleSearchIndex[i][0] === query);

		// Even with output buffer buckets, we make multiple passes through
		// the search index. searchPasses is a queue of which pass we're on:
		// [passType, i, query]

		// By doing an alias pass after the normal pass, we ensure that
		// mid-word matches only display after start matches.
		let passType: SearchPassType | '' = '';
		/**
		 * pass types:
		 * * '': time to pop the next pass off the searchPasses queue
		 * * 'normal': start at i and stop when results no longer start with query
		 * * 'alias': like normal, but output aliases instead of non-alias results
		 * * 'fuzzy': start at i and stop when you have two results
		 * * 'exact': like normal, but stop at i
		 */
		type SearchPassType = 'normal' | 'alias' | 'fuzzy' | 'exact';
		/**
		 * [passType, i, query]
		 *
		 * i = index of BattleSearchIndex to start from
		 *
		 * By doing an alias pass after the normal pass, we ensure that
		 * mid-word matches only display after start matches.
		 */
		type SearchPass = [SearchPassType, number, string];
		let searchPasses: SearchPass[] = [['normal', i, query]];

		// For performance reasons, only do an alias pass if query is at
		// least 2 chars long
		if (query.length > 1) searchPasses.push(['alias', i, query]);

		// If the query matches an official alias in BattleAliases: These are
		// different from the aliases in the search index and are given
		// higher priority. We'll do a normal pass through the index with
		// the alias text before any other passes.
		let queryAlias;
		if (query in BattleAliases) {
			if (['sub', 'tr'].includes(query) || toID(BattleAliases[query]).slice(0, query.length) !== query) {
				queryAlias = toID(BattleAliases[query]);
				let aliasPassType: SearchPassType = (queryAlias === 'hiddenpower' ? 'exact' : 'normal');
				searchPasses.unshift([aliasPassType, DexSearch.getClosest(queryAlias), queryAlias]);
			}
			this.exactMatch = true;
		}

		// If there are no matches starting with query: Do a fuzzy match pass
		// Fuzzy matches will still be shown after alias matches
		if (!this.exactMatch && BattleSearchIndex[i][0].substr(0, query.length) !== query) {
			// No results start with this. Do a fuzzy match pass.
			let matchLength = query.length - 1;
			if (!i) i++;
			while (matchLength &&
				BattleSearchIndex[i][0].substr(0, matchLength) !== query.substr(0, matchLength) &&
				BattleSearchIndex[i - 1][0].substr(0, matchLength) !== query.substr(0, matchLength)) {
				matchLength--;
			}
			let matchQuery = query.substr(0, matchLength);
			while (i >= 1 && BattleSearchIndex[i - 1][0].substr(0, matchLength) === matchQuery) i--;
			searchPasses.push(['fuzzy', i, '']);
		}

		// We split the output buffers into 8 buckets.
		// Bucket 0 is usually unused, and buckets 1-7 represent
		// pokemon, types, moves, etc (see typeTable).

		// When we're done, the buffers are concatenated together to form
		// our results, with each buffer getting its own header, unlike
		// multiple-pass results, which have no header.

		// Notes:
		// - if we have a searchType, that searchType's buffer will be on top
		let bufs: SearchRow[][] = [[], [], [], [], [], [], [], [], [], []];
		let topbufIndex = -1;

		let count = 0;
		let nearMatch = false;

		/** [type, id, typeIndex] */
		let instafilter: [SearchType, ID, number] | null = null;
		let instafilterSort = [0, 1, 2, 5, 4, 3, 6, 7, 8];
		let illegal = this.typedSearch?.illegalReasons;

		// We aren't actually looping through the entirety of the searchIndex
		for (i = 0; i < BattleSearchIndex.length; i++) {
			if (!passType) {
				let searchPass = searchPasses.shift();
				if (!searchPass) break;
				passType = searchPass[0];
				i = searchPass[1];
				query = searchPass[2];
			}

			let entry = BattleSearchIndex[i];
			let id = entry[0];
			let type = entry[1];

			if (!id) break;

			if (passType === 'fuzzy') {
				// fuzzy match pass; stop after 2 results
				if (count >= 2) {
					passType = '';
					continue;
				}
				nearMatch = true;
			} else if (passType === 'exact') {
				// exact pass; stop after 1 result
				if (count >= 1) {
					passType = '';
					continue;
				}
			} else if (id.substr(0, query.length) !== query) {
				// regular pass, time to move onto our next match
				passType = '';
				continue;
			}

			if (entry.length > 2) {
				// alias entry
				if (passType !== 'alias') continue;
			} else {
				// normal entry
				if (passType === 'alias') continue;
			}

			let typeIndex = DexSearch.typeTable[type];
			// For performance, with a query length of 1, we only fill the first bucket
			if (query.length === 1 && typeIndex !== (searchType ? searchTypeIndex : 1)) continue;

			// For pokemon queries, accept types/tier/abilities/moves/eggroups as filters
			if (searchType === 'pokemon' && (typeIndex === 5 || typeIndex > 7)) continue;
			if (searchType === 'pokemon' && typeIndex === 3 && this.dex.gen < 8) continue;
			// For move queries, accept types/categories as filters
			if (searchType === 'move' && ((typeIndex !== 8 && typeIndex > 4) || typeIndex === 3)) continue;
			// For move queries in the teambuilder, don't accept pokemon as filters
			if (searchType === 'move' && illegal && typeIndex === 1) continue;
			// For ability/item queries, don't accept anything else as a filter
			if ((searchType === 'ability' || searchType === 'item') && typeIndex !== searchTypeIndex) continue;
			// Query was a type name followed 'type'; only show types
			if (qFilterType === 'type' && typeIndex !== 2) continue;
			// hardcode cases of duplicate non-consecutive aliases
			if ((id === 'megax' || id === 'megay') && 'mega'.startsWith(query)) continue;

			let matchStart = 0;
			let matchEnd = 0;
			if (passType === 'alias') {
				// alias entry
				// [aliasid, type, originalid, matchStart, originalindex]
				matchStart = entry[3]!;
				let originalIndex = entry[2]!;
				if (matchStart) {
					matchEnd = matchStart + query.length;
					matchStart += (BattleSearchIndexOffset[originalIndex][matchStart] || '0').charCodeAt(0) - 48;
					matchEnd += (BattleSearchIndexOffset[originalIndex][matchEnd - 1] || '0').charCodeAt(0) - 48;
				}
				id = BattleSearchIndex[originalIndex][0];
			} else {
				matchEnd = query.length;
				if (matchEnd) matchEnd += (BattleSearchIndexOffset[i][matchEnd - 1] || '0').charCodeAt(0) - 48;
			}

			// some aliases are substrings
			if (queryAlias === id && query !== id) continue;

			if (searchType && searchTypeIndex !== typeIndex) {
				// This is a filter, set it as an instafilter candidate
				if (!instafilter || instafilterSort[typeIndex] < instafilterSort[instafilter[2]]) {
					instafilter = [type, id, typeIndex];
				}
			}

			// show types above Arceus formes
			if (topbufIndex < 0 && searchTypeIndex < 2 && passType === 'alias' && !bufs[1].length && bufs[2].length) {
				topbufIndex = 2;
			}

			// determine if the element comes from the current mod
			const table = BattleTeambuilderTable[window.room.curTeam.mod];
			if (
				typeIndex === 1 && (!BattlePokedex[id] || BattlePokedex[id].exists === false) &&
				(!table || !table.overrideDexInfo || id in table.overrideDexInfo === false)
			) continue;
			else if (
				typeIndex === 5 && (!BattleItems[id] || BattleItems[id].exists === false) &&
				(!table || !table.overrideItemDesc || id in table.overrideItemDesc === false)
			) continue;
			else if (
				typeIndex === 4 && (!BattleMovedex[id] || BattleMovedex[id].exists === false) &&
				(!table || !table.overrideMoveInfo || id in table.overrideMoveInfo === false)
			) continue;
			else if (
				typeIndex === 6 && (!BattleAbilities[id] || BattleAbilities[id].exists === false) &&
				(!table || !table.overrideAbilityDesc || id in table.overrideAbilityDesc === false)
			) continue;
			else if (
				typeIndex === 2 && id.replace(id.charAt(0), id.charAt(0).toUpperCase()) in window.BattleTypeChart === false &&
				(!table || id.replace(id.charAt(0), id.charAt(0).toUpperCase()) in table.overrideTypeChart === false)
			) continue;

			if (illegal && typeIndex === searchTypeIndex) {
				// Always show illegal results under legal results.
				// This is done by putting legal results (and the type header)
				// in bucket 0, and illegal results in the searchType's bucket.
				// searchType buckets are always on top (but under bucket 0), so
				// illegal results will be seamlessly right under legal results.
				if (!bufs[typeIndex].length && !bufs[0].length) {
					bufs[0] = [['header', DexSearch.typeName[type]]];
				}
				if (!(id in illegal)) typeIndex = 0;
			} else {
				if (!bufs[typeIndex].length) {
					bufs[typeIndex] = [['header', DexSearch.typeName[type]]];
				}
			}

			// don't match duplicate aliases
			let curBufLength = (passType === 'alias' && bufs[typeIndex].length);
			if (curBufLength && bufs[typeIndex][curBufLength - 1][1] === id) continue;

			bufs[typeIndex].push([type, id, matchStart, matchEnd]);
			count++;
		}

		let topbuf: SearchRow[] = [];
		if (nearMatch) {
			topbuf = [['html', `<em>No exact match found. The closest matches alphabetically are:</em>`]];
		}
		if (topbufIndex >= 0) {
			topbuf = topbuf.concat(bufs[topbufIndex]);
			bufs[topbufIndex] = [];
		}
		if (searchTypeIndex >= 0) {
			topbuf = topbuf.concat(bufs[0]);
			topbuf = topbuf.concat(bufs[searchTypeIndex]);
			bufs[searchTypeIndex] = [];
			bufs[0] = [];
		}

		if (instafilter && count < 20) {
			// Result count is less than 20, so we can instafilter
			bufs.push(this.instafilter(searchType, instafilter[0], instafilter[1]));
		}

		this.results = Array.prototype.concat.apply(topbuf, bufs);
		return this.results;
	}
	private instafilter(searchType: SearchType | '', fType: SearchType, fId: ID): SearchRow[] {
		let buf: SearchRow[] = [];
		let illegalBuf: SearchRow[] = [];
		let illegal = this.typedSearch?.illegalReasons;
		// Change object to look in if using a mod
		let pokedex = BattlePokedex;
		let moveDex = BattleMovedex;
		if (window.room.curTeam.mod) {
			pokedex = {};
			moveDex = {};
			const table = BattleTeambuilderTable[window.room.curTeam.mod];
			for (const id in table.overrideDexInfo) {
				pokedex[id] = {
					types: table.overrideDexInfo[id].types,
					abilities: table.overrideDexInfo[id].abilities,
				};
			}
			for (const id in table.overrideMoveInfo) {
				moveDex[id] = {
					type: table.overrideMoveInfo.type,
					category: table.overrideMoveInfo.category,
				};
			}
			pokedex = {...pokedex, ...BattlePokedex};
			moveDex = {...moveDex, ...BattleMovedex};
		}
		if (searchType === 'pokemon') {
			switch (fType) {
			case 'type':
				let type = fId.charAt(0).toUpperCase() + fId.slice(1) as TypeName;
				buf.push(['header', `${type}-type Pok&eacute;mon`]);
				for (let id in pokedex) {
					if (!pokedex[id].types) continue;
					if (this.dex.getSpecies(id).types.includes(type)) {
						(illegal && id in illegal ? illegalBuf : buf).push(['pokemon', id as ID]);
					}
				}
				break;
			case 'ability':
				let ability = this.dex.getAbility(fId).name;
				buf.push(['header', `${ability} Pok&eacute;mon`]);
				for (let id in pokedex) {
					if (!pokedex[id].abilities) continue;
					if (Dex.hasAbility(this.dex.getSpecies(id), ability)) {
						(illegal && id in illegal ? illegalBuf : buf).push(['pokemon', id as ID]);
					}
				}
				break;
			}
		} else if (searchType === 'move') {
			switch (fType) {
			case 'type':
				let type = fId.charAt(0).toUpperCase() + fId.slice(1);
				buf.push(['header', `${type}-type moves`]);
				for (let id in moveDex) {
					if (moveDex[id].type === type) {
						(illegal && id in illegal ? illegalBuf : buf).push(['move', id as ID]);
					}
				}
				break;
			case 'category':
				let category = fId.charAt(0).toUpperCase() + fId.slice(1);
				buf.push(['header', `${category} moves`]);
				for (let id in moveDex) {
					if (moveDex[id].category === category) {
						(illegal && id in illegal ? illegalBuf : buf).push(['move', id as ID]);
					}
				}
				break;
			}
		}
		return [...buf, ...illegalBuf];
	}

	static getClosest(query: string) {
		// binary search through the index!
		let left = 0;
		let right = BattleSearchIndex.length - 1;
		while (right > left) {
			let mid = Math.floor((right - left) / 2 + left);
			if (BattleSearchIndex[mid][0] === query && (mid === 0 || BattleSearchIndex[mid - 1][0] !== query)) {
				// that's us
				return mid;
			} else if (BattleSearchIndex[mid][0] < query) {
				left = mid + 1;
			} else {
				right = mid - 1;
			}
		}
		if (left >= BattleSearchIndex.length - 1) left = BattleSearchIndex.length - 1;
		else if (BattleSearchIndex[left + 1][0] && BattleSearchIndex[left][0] < query) left++;
		if (left && BattleSearchIndex[left - 1][0] === query) left--;
		return left;
	}
}

abstract class BattleTypedSearch<T extends SearchType> {
	searchType: T;
	/**
	 * Dex for the mod/generation to search.
	 */
	dex: ModdedDex = Dex;
	/**
	 * Format is the first of two base filters. It constrains results to things
	 * legal in the format, and affects the default sort.
	 *
	 * This string specifically normalizes out generation number and the words
	 * "Doubles" and "Let's Go" from the name.
	 *
	 * mod formats can set the format variable to a standard format, so modFormat
	 * keeps track of the original format in such a case
	 */
	format = '' as ID;
	modFormat = '' as ID;
	/**
	 * `species` is the second of two base filters. It constrains results to
	 * things that species can use, and affects the default sort.
	 */
	species = '' as ID;
	/**
	 * `set` is a pseudo-base filter; it has minor effects on move sorting.
	 * (Abilities/items can affect what moves are sorted as usable.)
	 */
	set: PokemonSet | null = null;
	mod = '';

	protected formatType: 'doubles' | 'letsgo' | 'metronome' | 'natdex' | 'nfe' | 'dlc1' | 'dlc1doubles' | null = null;

	/**
	 * Cached copy of what the results list would be with only base filters
	 * (i.e. with an empty `query` and `filters`)
	 */
	baseResults: SearchRow[] | null = null;
	/**
	 * Cached copy of all results not in `baseResults` - mostly in case a user
	 * is wondering why a specific result isn't showing up.
	 */
	baseIllegalResults: SearchRow[] | null = null;
	illegalReasons: {[id: string]: string} | null = null;
	results: SearchRow[] | null = null;

	protected readonly sortRow: SearchRow | null = null;

	constructor(searchType: T, format = '' as ID, speciesOrSet: ID | PokemonSet = '' as ID) {
		this.searchType = searchType;
		this.baseResults = null;
		this.baseIllegalResults = null;
		this.modFormat = format;
		let gen = 8;
		const ClientMods = window.ModConfig;
		if (format.slice(0, 3) === 'gen') {
			gen = (Number(format.charAt(3)) || 6);
			let mod = '';
			let overrideFormat = '';
			let modFormatType = '';
			for (const modid in (ClientMods)) {
				for (const formatid in ClientMods[modid].formats) {
					if (formatid === format) {
						mod = modid;
						const formatTable = ClientMods[modid].formats[formatid];
						if (mod && formatTable.teambuilderFormat) overrideFormat = toID(formatTable.teambuilderFormat);
						if (mod && formatTable.formatType) modFormatType = toID(formatTable.formatType);
						break;
					}
				}
			}
			if (mod) {
				this.dex = Dex.mod(mod as ID);
				this.dex.gen = gen;
				this.mod = mod;
			} else {
				this.dex = Dex.forGen(gen);
			}
			if (overrideFormat) format = overrideFormat as ID;
			else format = (format.slice(4) || 'customgame') as ID;
			if (modFormatType) this.formatType = modFormatType as 'doubles' | 'letsgo' | 'metronome' | 'natdex' | 'nfe' | 'dlc1' | 'dlc1doubles' | null;
		} else if (!format) {
			this.dex = Dex;
		}
		if (format.startsWith('dlc1')) {
			if (format.includes('doubles')) {
				this.formatType = 'dlc1doubles';
			} else {
				this.formatType = 'dlc1';
			}
			format = format.slice(4) as ID;
		}
		if (format === 'vgc2020') this.formatType = 'dlc1doubles';
		if (format.includes('doubles') && this.dex.gen > 4 && !this.formatType) this.formatType = 'doubles';
		if (format.includes('letsgo')) this.formatType = 'letsgo';
		if (format.includes('nationaldex')) {
			format = format.slice(11) as ID;
			this.formatType = 'natdex';
			if (!format) format = 'ou' as ID;
		}
		if (this.formatType === 'letsgo') format = format.slice(6) as ID;
		if (format.includes('metronome')) {
			this.formatType = 'metronome';
		}
		if (format.endsWith('nfe')) {
			format = format.slice(3) as ID;
			this.formatType = 'nfe';
			if (!format) format = 'ou' as ID;
		}
		this.format = format;

		this.species = '' as ID;
		this.set = null;
		if (typeof speciesOrSet === 'string') {
			if (speciesOrSet) this.species = speciesOrSet;
		} else {
			this.set = speciesOrSet as PokemonSet;
			this.species = toID(this.set.species);
		}
		if (!searchType || !this.set) return;
	}
	getResults(filters?: SearchFilter[] | null, sortCol?: string | null): SearchRow[] {
		if (sortCol === 'type') {
			return [this.sortRow!, ...BattleTypeSearch.prototype.getDefaultResults.call(this)];
		} else if (sortCol === 'category') {
			return [this.sortRow!, ...BattleCategorySearch.prototype.getDefaultResults.call(this)];
		} else if (sortCol === 'ability') {
			return [this.sortRow!, ...BattleAbilitySearch.prototype.getDefaultResults.call(this)];
		}
		if (!this.baseResults) {
			this.baseResults = this.getBaseResults();
		}

		if (!this.baseIllegalResults) {
			const legalityFilter: {[id: string]: 1} = {};
			for (const [resultType, value] of this.baseResults) {
				if (resultType === this.searchType) legalityFilter[value] = 1;
			}
			this.baseIllegalResults = [];
			this.illegalReasons = {};

			for (const id in this.getTable()) {
				if (!(id in legalityFilter)) {
					this.baseIllegalResults.push([this.searchType, id as ID]);
					this.illegalReasons[id] = 'Illegal';
				}
			}
		}

		let results: SearchRow[];
		let illegalResults: SearchRow[] | null;

		if (filters) {
			results = [];
			illegalResults = [];
			for (const result of this.baseResults) {
				if (this.filter(result, filters)) {
					if (results.length && result[0] === 'header' && results[results.length - 1][0] === 'header') {
						results[results.length - 1] = result;
					} else {
						results.push(result);
					}
				}
			}
			if (results.length && results[results.length - 1][0] === 'header') {
				results.pop();
			}
			for (const result of this.baseIllegalResults) {
				if (this.filter(result, filters)) {
					illegalResults.push(result);
				}
			}
		} else {
			results = [...this.baseResults];
			illegalResults = null;
		}

		if (sortCol) {
			results = results.filter(([rowType]) => rowType === this.searchType);
			results = this.sort(results, sortCol);
			if (illegalResults) {
				illegalResults = illegalResults.filter(([rowType]) => rowType === this.searchType);
				illegalResults = this.sort(illegalResults, sortCol);
			}
		}

		if (this.sortRow) {
			results = [this.sortRow, ...results];
		}
		if (illegalResults && illegalResults.length) {
			results = [...results, ['header', "Illegal results"], ...illegalResults];
		}
		return results;
	}
	protected firstLearnsetid(speciesid: ID) {
		let learnsets = BattleTeambuilderTable.learnsets;
		if (speciesid in learnsets) return speciesid;
		const species = this.dex.getSpecies(speciesid);
		if (!species.exists) return '' as ID;
		let baseLearnsetid = toID(species.baseSpecies);
		if (typeof species.battleOnly === 'string' && species.battleOnly !== species.baseSpecies) {
			baseLearnsetid = toID(species.battleOnly);
		}
		if (baseLearnsetid in learnsets) return baseLearnsetid;
		return '' as ID;
	}
	protected nextLearnsetid(learnsetid: ID, speciesid: ID) {
		if (learnsetid === 'lycanrocdusk' || (speciesid === 'rockruff' && learnsetid === 'rockruff')) {
			return 'rockruffdusk' as ID;
		}
		const lsetSpecies = this.dex.getSpecies(learnsetid);
		if (!lsetSpecies.exists) return '' as ID;

		if (lsetSpecies.id === 'gastrodoneast') return 'gastrodon' as ID;
		if (lsetSpecies.id === 'pumpkaboosuper') return 'pumpkaboo' as ID;

		const next = lsetSpecies.battleOnly || lsetSpecies.changesFrom || lsetSpecies.prevo;
		if (next) return toID(next);

		return '' as ID;
	}
	protected canLearn(speciesid: ID, moveid: ID) {
		if (this.dex.gen >= 8 && this.dex.getMove(moveid).isNonstandard === 'Past' && this.formatType !== 'natdex') {
			return false;
		}
		let genChar = `${this.dex.gen}`;
		if (
			this.format.startsWith('vgc') ||
			this.format.startsWith('battlespot') ||
			this.format.startsWith('battlestadium')
		) {
			if (this.dex.gen === 8) {
				genChar = 'g';
			} else if (this.dex.gen === 7) {
				genChar = 'q';
			} else if (this.dex.gen === 6) {
				genChar = 'p';
			}
		}
		let learnsetid = this.firstLearnsetid(speciesid);
		while (learnsetid) {
			let learnset = BattleTeambuilderTable.learnsets[learnsetid];
			if (this.mod) {
				const overrideLearnsets = BattleTeambuilderTable[this.mod].overrideLearnsets;
				if (overrideLearnsets[learnsetid] && overrideLearnsets[learnsetid][moveid]) learnset = overrideLearnsets[learnsetid];
			}
			if (learnset && (moveid in learnset) && learnset[moveid].includes(genChar)) {
				return true;
			}
			learnsetid = this.nextLearnsetid(learnsetid, speciesid);
		}
		return false;
	}
	getTier(pokemon: Species) {
		if (this.formatType === 'metronome' || this.formatType === 'natdex') {
			return pokemon.num >= 0 ? String(pokemon.num) : pokemon.tier;
		}
		const modFormatTable = this.mod ? window.ModConfig[this.mod].formats[this.modFormat] : {};
		let table = window.BattleTeambuilderTable;
		if (this.mod) table = modFormatTable.gameType !== 'doubles' ? BattleTeambuilderTable[this.mod] : BattleTeambuilderTable[this.mod].doubles;
		const tableKey = this.formatType === 'doubles' ? `gen${this.dex.gen}doubles` :
			this.formatType === 'letsgo' ? 'letsgo' :
			this.formatType === 'nfe' ? `gen${this.dex.gen}nfe` :
			this.formatType === 'dlc1' ? 'gen8dlc1' :
			this.formatType === 'dlc1doubles' ? 'gen8dlc1doubles' :
			`gen${this.dex.gen}`;
		if (table && table[tableKey]) {
			table = table[tableKey];
		}
		if (!table) return pokemon.tier;
		let id = pokemon.id;
		if (id in table.overrideTier) {
			return table.overrideTier[id];
		}
		if (id.slice(-5) === 'totem' && id.slice(0, -5) in table.overrideTier) {
			return table.overrideTier[id.slice(0, -5)];
		}
		id = toID(pokemon.baseSpecies);
		if (id in table.overrideTier) {
			return table.overrideTier[id];
		}
		return pokemon.tier;
	}
	abstract getTable(): {[id: string]: any};
	abstract getDefaultResults(): SearchRow[];
	abstract getBaseResults(): SearchRow[];
	abstract filter(input: SearchRow, filters: string[][]): boolean;
	abstract sort(input: SearchRow[], sortCol: string): SearchRow[];
}

class BattlePokemonSearch extends BattleTypedSearch<'pokemon'> {
	sortRow: SearchRow = ['sortpokemon', ''];
	getTable() {
		if (!this.mod) return BattlePokedex;
		else return {...BattleTeambuilderTable[this.mod].overrideDexInfo, ...BattlePokedex};
	}
	getDefaultResults(): SearchRow[] {
		let results: SearchRow[] = [];
		for (let id in BattlePokedex) {
			switch (id) {
			case 'bulbasaur':
				results.push(['header', "Generation 1"]);
				break;
			case 'chikorita':
				results.push(['header', "Generation 2"]);
				break;
			case 'treecko':
				results.push(['header', "Generation 3"]);
				break;
			case 'turtwig':
				results.push(['header', "Generation 4"]);
				break;
			case 'victini':
				results.push(['header', "Generation 5"]);
				break;
			case 'chespin':
				results.push(['header', "Generation 6"]);
				break;
			case 'rowlet':
				results.push(['header', "Generation 7"]);
				break;
			case 'grookey':
				results.push(['header', "Generation 8"]);
				break;
			case 'missingno':
				results.push(['header', "Glitch"]);
				break;
			case 'tomohawk':
				results.push(['header', "CAP"]);
				break;
			case 'pikachucosplay':
				continue;
			}
			results.push(['pokemon', id as ID]);
		}
		return results;
	}
	getBaseResults(): SearchRow[] {
		const format = this.format;
		if (!format) return this.getDefaultResults();
		const requirePentagon = format === 'battlespotsingles' || format === 'battledoubles' || format.startsWith('vgc');
		let isDoublesOrBS = this.formatType === 'doubles';
		const dex = this.dex;
		const modFormatTable = this.mod ? window.ModConfig[this.mod].formats[this.modFormat] : {};
		let table = BattleTeambuilderTable;
		if (this.mod) {
			table = modFormatTable.gameType !== 'doubles' ? BattleTeambuilderTable[this.mod] : BattleTeambuilderTable[this.mod].doubles;
		} else if (format.endsWith('cap') || format.endsWith('caplc')) {
			// CAP formats always use the singles table
			if (dex.gen < 8) {
				table = table['gen' + dex.gen];
			}
		} else if (dex.gen === 7 && requirePentagon) {
			table = table['gen' + dex.gen + 'vgc'];
			isDoublesOrBS = true;
		} else if (table['gen' + dex.gen + 'doubles'] && dex.gen > 4 && this.formatType !== 'letsgo' && this.formatType !== 'dlc1doubles' &&
			(
			format.includes('doubles') || format.includes('vgc') || format.includes('triples') ||
			format.endsWith('lc') || format.endsWith('lcuu')
		)) {
			table = table['gen' + dex.gen + 'doubles'];
			isDoublesOrBS = true;
		} else if (dex.gen < 8 && !this.formatType) {
			table = table['gen' + dex.gen];
		} else if (this.formatType === 'letsgo') {
			table = table['letsgo'];
		} else if (this.formatType === 'natdex') {
			table = table['natdex'];
		} else if (this.formatType === 'metronome') {
			table = table['metronome'];
		} else if (this.formatType === 'nfe') {
			table = table['gen' + dex.gen + 'nfe'];
		} else if (this.formatType?.startsWith('dlc1')) {
			if (this.formatType.includes('doubles')) {
				table = table['gen8dlc1doubles'];
			} else {
				table = table['gen8dlc1'];
			}
		}
		if (!table.tierSet) {
			table.tierSet = table.tiers.map((r: any) => {
				if (typeof r === 'string') return ['pokemon', r];
				return [r[0], r[1]];
			});
			table.tiers = null;
		}
		let tierSet: SearchRow[] = table.tierSet;
		let slices: {[k: string]: number} = table.formatSlices;
		if (format === 'ubers' || format === 'uber') tierSet = tierSet.slice(slices.Uber);
		else if (format === 'vgc2017') tierSet = tierSet.slice(slices.Regular);
		else if (format === 'vgc2018') tierSet = tierSet.slice(slices.Regular);
		else if (format.startsWith('vgc2019')) tierSet = tierSet.slice(slices["Restricted Legendary"]);
		else if (format === 'battlespotsingles') tierSet = tierSet.slice(slices.Regular);
		else if (format === 'battlespotdoubles') tierSet = tierSet.slice(slices.Regular);
		else if (format === 'ou') tierSet = tierSet.slice(slices.OU);
		else if (format === 'uu') tierSet = tierSet.slice(slices.UU);
		else if (format === 'ru') tierSet = tierSet.slice(slices.RU || slices.UU);
		else if (format === 'nu') tierSet = tierSet.slice(slices.NU || slices.UU);
		else if (format === 'pu') tierSet = tierSet.slice(slices.PU || slices.NU);
		else if (format === 'zu') tierSet = tierSet.slice(slices.ZU || slices.PU || slices.NU);
		else if (format === 'lc' || format === 'lcuu' || format.startsWith('lc') || (format !== 'caplc' && format.endsWith('lc'))) tierSet = tierSet.slice(slices.LC);
		else if (format === 'cap') tierSet = tierSet.slice(0, slices.Uber).concat(tierSet.slice(slices.OU));
		else if (format === 'caplc') tierSet = tierSet.slice(slices['CAP LC'], slices.Uber).concat(tierSet.slice(slices.LC));
		else if (format === 'anythinggoes' || format.endsWith('ag')) tierSet = tierSet.slice(slices.AG);
		else if (format === 'balancedhackmons' || format.endsWith('bh')) tierSet = tierSet.slice(slices.AG);
		else if (format === 'doublesubers') tierSet = tierSet.slice(slices.DUber);
		else if (format === 'doublesou' && dex.gen > 4) tierSet = tierSet.slice(slices.DOU);
		else if (format === 'doublesuu') tierSet = tierSet.slice(slices.DUU);
		else if (format === 'doublesnu') tierSet = tierSet.slice(slices.DNU || slices.DUU);
		else if (this.formatType === 'letsgo') tierSet = tierSet.slice(slices.Uber);
		// else if (isDoublesOrBS) tierSet = tierSet;
		else if (!isDoublesOrBS) {
			tierSet = [
				...tierSet.slice(slices.OU, slices.UU),
				...tierSet.slice(slices.AG, slices.Uber),
				...tierSet.slice(slices.Uber, slices.OU),
				...tierSet.slice(slices.UU),
			];
		} else {
			tierSet = [
				...tierSet.slice(slices.DOU, slices.DUU),
				...tierSet.slice(slices.DUber, slices.DOU),
				...tierSet.slice(slices.DUU),
			];
		}

		if (format === 'zu' && dex.gen >= 7) {
			tierSet = tierSet.filter(([type, id]) => {
				if (id in table.zuBans) return false;
				return true;
			});
		}
		if (format === 'vgc2016') {
			tierSet = tierSet.filter(([type, id]) => {
				let banned = [
					'deoxys', 'deoxysattack', 'deoxysdefense', 'deoxysspeed', 'mew', 'celebi', 'shaymin', 'shayminsky', 'darkrai', 'victini', 'keldeo', 'keldeoresolute', 'meloetta', 'arceus', 'genesect', 'jirachi', 'manaphy', 'phione', 'hoopa', 'hoopaunbound', 'diancie', 'dianciemega',
				];
				return !(banned.includes(id) || id.startsWith('arceus'));
			});
		}

		if (this.mod && !table.customTierSet) {
			table.customTierSet = table.customTiers.map((r: any) => {
				if (typeof r === 'string') return ['pokemon', r];
				return [r[0], r[1]];
			});
			table.customTiers = null;
		}
		let customTierSet: SearchRow[] = table.customTierSet;
		if (customTierSet) {
			tierSet = customTierSet.concat(tierSet);
			if (modFormatTable.bans.length > 0 && !modFormatTable.bans.includes("All Pokemon")) {
				tierSet = tierSet.filter(([type, id]) => {
					let banned = modFormatTable.bans;
					return !(banned.includes(id));
				});
			} else if (modFormatTable.unbans.length > 0 && modFormatTable.bans.includes("All Pokemon")) {
				tierSet = tierSet.filter(([type, id]) => {
					let unbanned = modFormatTable.unbans;
					return (unbanned.includes(id) || type === 'header');
				});
			}
			let headerCount = 0;
			let lastHeader = '';
			const emptyHeaders: string[] = [];
			for (const i in tierSet) {
				headerCount = tierSet[i][0] === 'header' ? headerCount + 1 : 0;
				if (headerCount > 1) emptyHeaders.push(lastHeader);
				if (headerCount > 0) lastHeader = tierSet[i][1];
			}
			if (headerCount === 1) emptyHeaders.push(lastHeader);
			tierSet = tierSet.filter(([type, id]) => {
				return (type !== 'header' || !emptyHeaders.includes(id));
			});
		}

		// Filter out Gmax Pokemon from standard tier selection
		if (!/^(battlestadium|vgc|doublesubers)/g.test(format)) {
			tierSet = tierSet.filter(([type, id]) => {
				if (type === 'pokemon' && !this.mod) {
					return !id.endsWith('gmax');
				}
				return true;
			});
		}

		return tierSet;
	}
	filter(row: SearchRow, filters: string[][]) {
		if (!filters) return true;
		if (row[0] !== 'pokemon') return true;
		const species = this.dex.getSpecies(row[1]);
		for (const [filterType, value] of filters) {
			switch (filterType) {
			case 'type':
				if (species.types[0] !== value && species.types[1] !== value) return false;
				break;
			case 'egggroup':
				if (species.eggGroups[0] !== value && species.eggGroups[1] !== value) return false;
				break;
			case 'tier':
				if (this.getTier(species) !== value) return false;
				break;
			case 'ability':
				if (!Dex.hasAbility(species, value)) return false;
				break;
			case 'move':
				if (!this.canLearn(species.id, value as ID)) return false;
			}
		}
		return true;
	}
	sort(results: SearchRow[], sortCol: string) {
		const table = !this.mod ? '' : BattleTeambuilderTable[this.mod].overrideDexInfo;
		if (['hp', 'atk', 'def', 'spa', 'spd', 'spe'].includes(sortCol)) {
			return results.sort(([rowType1, id1], [rowType2, id2]) => {
				let pokedex1 = BattlePokedex;
				let pokedex2 = BattlePokedex;
				if (this.mod) {
					if (table[id1] && table[id1].baseStats) pokedex1 = table;
					if (table[id2] && table[id2].baseStats) pokedex2 = table;
				}
				const stat1 = pokedex1[id1].baseStats[sortCol as StatName];
				const stat2 = pokedex2[id2].baseStats[sortCol as StatName];
				return stat2 - stat1;
			});
		} else if (sortCol === 'bst') {
			return results.sort(([rowType1, id1], [rowType2, id2]) => {
				let pokedex1 = BattlePokedex;
				let pokedex2 = BattlePokedex;
				if (this.mod) {
					if (table[id1] && table[id1].baseStats) pokedex1 = table;
					if (table[id2] && table[id2].baseStats) pokedex2 = table;
				}
				const base1 = pokedex1[id1].baseStats;
				const base2 = pokedex2[id2].baseStats;
				const bst1 = base1.hp + base1.atk + base1.def + base1.spa + base1.spd + base1.spe;
				const bst2 = base2.hp + base2.atk + base2.def + base2.spa + base2.spd + base2.spe;
				return bst2 - bst1;
			});
		} else if (sortCol === 'name') {
			return results.sort(([rowType1, id1], [rowType2, id2]) => {
				const name1 = id1;
				const name2 = id2;
				return name1 < name2 ? -1 : name1 > name2 ? 1 : 0;
			});
		}
		throw new Error("invalid sortcol");
	}
}

class BattleAbilitySearch extends BattleTypedSearch<'ability'> {
	getTable() {
		if (!this.mod) return BattleAbilities;
		else return {...BattleTeambuilderTable[this.mod].fullAbilityName, ...BattleAbilities};
	}
	getDefaultResults(): SearchRow[] {
		const results: SearchRow[] = [];
		for (let id in BattleAbilities) {
			results.push(['ability', id as ID]);
		}
		return results;
	}
	getBaseResults() {
		if (!this.species) return this.getDefaultResults();
		const format = this.format;
		const isHackmons = (format.includes('hackmons') || format.endsWith('bh'));
		const isAAA = (format === 'almostanyability' || format.includes('aaa'));
		const dex = this.dex;
		let species = dex.getSpecies(this.species);
		let abilitySet: SearchRow[] = [['header', "Abilities"]];

		if (species.isMega) {
			abilitySet.unshift(['html', `Will be <strong>${species.abilities['0']}</strong> after Mega Evolving.`]);
			species = dex.getSpecies(species.baseSpecies);
		}
		abilitySet.push(['ability', toID(species.abilities['0'])]);
		if (species.abilities['1']) {
			abilitySet.push(['ability', toID(species.abilities['1'])]);
		}
		if (species.abilities['H']) {
			abilitySet.push(['header', "Hidden Ability"]);
			abilitySet.push(['ability', toID(species.abilities['H'])]);
		}
		if (species.abilities['S']) {
			abilitySet.push(['header', "Special Event Ability"]);
			abilitySet.push(['ability', toID(species.abilities['S'])]);
		}
		if (isAAA || format === 'metronomebattle' || isHackmons) {
			let abilities: ID[] = [];
			for (let i in this.getTable()) {
				const ability = dex.getAbility(i);
				if (ability.isNonstandard) continue;
				if (ability.gen > dex.gen) continue;
				abilities.push(ability.id);
			}

			let goodAbilities: SearchRow[] = [['header', "Abilities"]];
			let poorAbilities: SearchRow[] = [['header', "Situational Abilities"]];
			let badAbilities: SearchRow[] = [['header', "Unviable Abilities"]];
			for (const ability of abilities.sort().map(abil => dex.getAbility(abil))) {
				let rating = ability.rating;
				if (ability.id === 'normalize') rating = 3;
				if (rating >= 3) {
					goodAbilities.push(['ability', ability.id]);
				} else if (rating >= 2) {
					poorAbilities.push(['ability', ability.id]);
				} else {
					badAbilities.push(['ability', ability.id]);
				}
			}
			abilitySet = [...goodAbilities, ...poorAbilities, ...badAbilities];
			if (species.isMega) {
				if (isAAA) {
					abilitySet.unshift(['html', `Will be <strong>${species.abilities['0']}</strong> after Mega Evolving.`]);
				}
				// species is unused after this, so no need to replace
			}
		}
		return abilitySet;
	}
	filter(row: SearchRow, filters: string[][]) {
		if (!filters) return true;
		if (row[0] !== 'ability') return true;
		const ability = this.dex.getAbility(row[1]);
		for (const [filterType, value] of filters) {
			switch (filterType) {
			case 'pokemon':
				if (!Dex.hasAbility(this.dex.getSpecies(value), ability.name)) return false;
				break;
			}
		}
		return true;
	}
	sort(results: SearchRow[], sortCol: string | null): SearchRow[] {
		throw new Error("invalid sortcol");
	}
}

class BattleItemSearch extends BattleTypedSearch<'item'> {
	getTable() {
		if (!this.mod) return BattleItems;
		else return {...BattleTeambuilderTable[this.mod].fullItemName, ...BattleItems};
	}
	getDefaultResults(): SearchRow[] {
		let table = BattleTeambuilderTable;
		if (this.mod) {
			table = table[this.mod];
		} else if (this.dex.gen < 8) {
			table = table['gen' + this.dex.gen];
		} else if (this.formatType === 'natdex') {
			table = table['natdex'];
		} else if (this.formatType === 'metronome') {
			table = table['metronome'];
		}
		if (!table.itemSet) {
			table.itemSet = table.items.map((r: any) => {
				if (typeof r === 'string') {
					return ['item', r];
				}
				return [r[0], r[1]];
			});
			table.items = null;
		}
		return table.itemSet;
	}
	getBaseResults(): SearchRow[] {
		if (!this.species) return this.getDefaultResults();
		const speciesName = this.dex.getSpecies(this.species).name;
		const results = this.getDefaultResults();
		const speciesSpecific: SearchRow[] = [];
		for (const row of results) {
			if (row[0] !== 'item') continue;
			if (this.dex.getItem(row[1]).itemUser?.includes(speciesName)) {
				speciesSpecific.push(row);
			}
		}
		if (speciesSpecific.length) {
			return [
				['header', "Specific to " + speciesName],
				...speciesSpecific,
				...results,
			];
		}
		return results;
	}
	filter(row: SearchRow, filters: string[][]) {
		if (!filters) return true;
		if (row[0] !== 'ability') return true;
		const ability = this.dex.getAbility(row[1]);
		for (const [filterType, value] of filters) {
			switch (filterType) {
			case 'pokemon':
				if (!Dex.hasAbility(this.dex.getSpecies(value), ability.name)) return false;
				break;
			}
		}
		return true;
	}
	sort(results: SearchRow[], sortCol: string | null): SearchRow[] {
		throw new Error("invalid sortcol");
	}
}

class BattleMoveSearch extends BattleTypedSearch<'move'> {
	sortRow: SearchRow = ['sortmove', ''];
	getTable() {
		if (!this.mod) return BattleMovedex;
		else return {...BattleTeambuilderTable[this.mod].overrideMoveInfo, ...BattleMovedex};
	}
	getDefaultResults(): SearchRow[] {
		let results: SearchRow[] = [];
		results.push(['header', "Moves"]);
		for (let id in BattleMovedex) {
			switch (id) {
			case 'paleowave':
				results.push(['header', "CAP moves"]);
				break;
			case 'magikarpsrevenge':
				continue;
			}
			results.push(['move', id as ID]);
		}
		return results;
	}
	private moveIsNotUseless(id: ID, species: Species, abilityid: ID, itemid: ID, moves: string[]) {
		const dex = this.dex;
		if (dex.gen === 1) {
			// Usually not useless for Gen 1
			if ([
				'acidarmor', 'amnesia', 'barrier', 'bind', 'blizzard', 'clamp', 'confuseray', 'counter', 'firespin', 'hyperbeam', 'mirrormove', 'pinmissile', 'razorleaf', 'sing', 'slash', 'sludge', 'twineedle', 'wrap',
			].includes(id)) {
				return true;
			}

			// Usually useless for Gen 1
			if ([
				'disable', 'firepunch', 'icepunch', 'leechseed', 'quickattack', 'roar', 'thunderpunch', 'toxic', 'triattack', 'whirlwind',
			].includes(id)) {
				return false;
			}
			// Not useless only when certain moves aren't present
			switch (id) {
			case 'bubblebeam': return (!moves.includes('surf') && !moves.includes('blizzard'));
			case 'doubleedge': return !moves.includes('bodyslam');
			case 'doublekick': return !moves.includes('submission');
			case 'megadrain': return !moves.includes('razorleaf') && !moves.includes('surf');
			case 'megakick': return !moves.includes('hyperbeam');
			case 'reflect': return !moves.includes('barrier') && !moves.includes('acidarmor');
			case 'submission': return !moves.includes('highjumpkick');
			}
		}

		if (this.formatType === 'letsgo') {
			if (id === 'megadrain') return true;
		}

		if (this.formatType === 'metronome') {
			if (id === 'metronome') return true;
		}

		if (itemid === 'pidgeotite') abilityid = 'noguard' as ID;
		if (itemid === 'blastoisinite') abilityid = 'megalauncher' as ID;
		if (itemid === 'aerodactylite') abilityid = 'toughclaws' as ID;
		if (itemid === 'glalitite') abilityid = 'refrigerate' as ID;

		switch (id) {
		case 'fakeout': case 'flamecharge': case 'nuzzle': case 'poweruppunch':
			return abilityid !== 'sheerforce';
		case 'solarbeam': case 'solarblade':
			return ['desolateland', 'drought', 'chlorophyll'].includes(abilityid) || itemid === 'powerherb';
		case 'dynamicpunch': case 'grasswhistle': case 'inferno': case 'sing': case 'zapcannon':
			return abilityid === 'noguard';
		case 'heatcrash': case 'heavyslam':
			return species.weightkg >= (species.evos ? 75 : 130);

		case 'aerialace':
			return ['technician', 'toughclaws'].includes(abilityid) && !moves.includes('bravebird');
		case 'ancientpower':
			return ['serenegrace', 'technician'].includes(abilityid) || !moves.includes('powergem');
		case 'aurawheel':
			return species.baseSpecies === 'Morpeko';
		case 'bellydrum':
			return moves.includes('aquajet') || moves.includes('extremespeed') ||
				['iceface', 'unburden'].includes(abilityid);
		case 'bulletseed':
			return ['skilllink', 'technician'].includes(abilityid);
		case 'counter':
			return species.baseStats.hp >= 65;
		case 'darkvoid':
			return dex.gen < 7;
		case 'drainingkiss':
			return abilityid === 'triage';
		case 'dualwingbeat':
			return abilityid === 'technician' || !moves.includes('drillpeck');
		case 'feint':
			return abilityid === 'refrigerate';
		case 'grassyglide':
			return abilityid === 'grassysurge';
		case 'gyroball':
			return species.baseStats.spe <= 60;
		case 'headbutt':
			return abilityid === 'serenegrace';
		case 'hiddenpowerelectric':
			return (dex.gen < 4 && !moves.includes('thunderpunch')) && !moves.includes('thunderbolt');
		case 'hiddenpowerfighting':
			return (dex.gen < 4 && !moves.includes('brickbreak')) && !moves.includes('aurasphere') && !moves.includes('focusblast');
		case 'hiddenpowerfire':
			return (dex.gen < 4 && !moves.includes('firepunch')) && !moves.includes('flamethrower');
		case 'hiddenpowergrass':
			return !moves.includes('energyball') && !moves.includes('grassknot') && !moves.includes('gigadrain');
		case 'hiddenpowerice':
			return !moves.includes('icebeam') && (dex.gen < 4 && !moves.includes('icepunch')) || (dex.gen > 5 && !moves.includes('aurorabeam'));
		case 'hiddenpowerflying':
			return dex.gen < 4 && !moves.includes('drillpeck');
		case 'hiddenpowerbug':
			return dex.gen < 4 && !moves.includes('megahorn');
		case 'hiddenpowerpsychic':
			return species.baseSpecies === 'Unown';
		case 'hyperspacefury':
			return species.id === 'hoopaunbound';
		case 'hypnosis':
			return (dex.gen < 4 && !moves.includes('sleeppowder')) || (dex.gen > 6 && abilityid === 'baddreams');
		case 'icywind':
			// Keldeo needs Hidden Power for Electric/Ghost
			return species.baseSpecies === 'Keldeo' || this.formatType === 'doubles';
		case 'infestation':
			return moves.includes('stickyweb');
		case 'irontail':
			return dex.gen > 5 && !moves.includes('ironhead') && !moves.includes('gunkshot') && !moves.includes('poisonjab');
		case 'jumpkick':
			return !moves.includes('highjumpkick');
		case 'leechlife':
			return dex.gen > 6;
		case 'mysticalfire':
			return dex.gen > 6 && !moves.includes('flamethrower');
		case 'naturepower':
			return dex.gen === 5;
		case 'nightslash':
			return !moves.includes('crunch') && !(moves.includes('knockoff') && dex.gen >= 6);
		case 'petaldance':
			return abilityid === 'owntempo';
		case 'phantomforce':
			return (!moves.includes('poltergeist') && !moves.includes('shadowclaw')) || this.formatType === 'doubles';
		case 'poisonfang':
			return species.types.includes('Poison') && !moves.includes('gunkshot') && !moves.includes('poisonjab');
		case 'relicsong':
			return species.id === 'meloetta';
		case 'refresh':
			return !moves.includes('aromatherapy') && !moves.includes('healbell');
		case 'risingvoltage':
			return abilityid === 'electricsurge';
		case 'rocktomb':
			return abilityid === 'technician';
		case 'selfdestruct':
			return dex.gen < 5 && !moves.includes('explosion');
		case 'shadowpunch':
			return abilityid === 'ironfist';
		case 'smackdown':
			return species.types.includes('Ground');
		case 'smartstrike':
			return species.types.includes('Steel') && !moves.includes('ironhead');
		case 'soak':
			return abilityid === 'unaware';
		case 'steelwing':
			return !moves.includes('ironhead');
		case 'stompingtantrum':
			return (!moves.includes('earthquake') && !moves.includes('drillrun')) || this.formatType === 'doubles';
		case 'stunspore':
			return !moves.includes('thunderwave');
		case 'technoblast':
			return dex.gen > 5 && itemid.endsWith('drive') || itemid === 'dousedrive';
		case 'teleport':
			return dex.gen > 7;
		case 'terrainpulse': case 'waterpulse':
			return ['megalauncher', 'technician'].includes(abilityid) && !moves.includes('originpulse');
		case 'trickroom':
			return species.baseStats.spe <= 100;
		}

		if (this.formatType === 'doubles' && BattleMoveSearch.GOOD_DOUBLES_MOVES.includes(id)) {
			return true;
		}
		// Custom move added by a mod
		if (this.mod && id in BattleTeambuilderTable[this.mod].overrideMoveInfo) return true;
		const moveData = BattleMovedex[id];
		if (!moveData) return true;
		if (moveData.category === 'Status') {
			return BattleMoveSearch.GOOD_STATUS_MOVES.includes(id);
		}
		if (moveData.basePower < 75) {
			return BattleMoveSearch.GOOD_WEAK_MOVES.includes(id);
		}
		if (id === 'skydrop') return true;
		// strong moves
		if (moveData.flags?.charge) {
			return itemid === 'powerherb';
		}
		if (moveData.flags?.recharge) {
			return false;
		}
		return !BattleMoveSearch.BAD_STRONG_MOVES.includes(id);
	}
	static readonly GOOD_STATUS_MOVES = [
		'agility', 'aromatherapy', 'auroraveil', 'autotomize', 'banefulbunker', 'batonpass', 'bellydrum', 'bulkup', 'calmmind', 'clangoroussoul', 'coil', 'cottonguard', 'courtchange', 'curse', 'defog', 'destinybond', 'detect', 'disable', 'dragondance', 'drainingkiss', 'encore', 'extremeevoboost', 'geomancy', 'glare', 'haze', 'healbell', 'healingwish', 'healorder', 'heartswap', 'honeclaws', 'kingsshield', 'irondefense', 'leechseed', 'lightscreen', 'lovelykiss', 'lunardance', 'magiccoat', 'maxguard', 'memento', 'milkdrink', 'moonlight', 'morningsun', 'nastyplot', 'naturesmadness', 'noretreat', 'obstruct', 'painsplit', 'partingshot', 'perishsong', 'protect', 'quiverdance', 'recover', 'reflect', 'reflecttype', 'rest', 'roar', 'rockpolish', 'roost', 'shellsmash', 'shiftgear', 'slackoff', 'sleeppowder', 'sleeptalk', 'softboiled', 'spikes', 'spikyshield', 'spore', 'stealthrock', 'stickyweb', 'strengthsap', 'substitute', 'switcheroo', 'swordsdance', 'synthesis', 'tailglow', 'tailwind', 'taunt', 'thunderwave', 'toxic', 'toxicspikes', 'transform', 'trick', 'whirlwind', 'willowisp', 'wish', 'yawn',
	] as ID[] as readonly ID[];
	static readonly GOOD_WEAK_MOVES = [
		'accelerock', 'acrobatics', 'aquajet', 'avalanche', 'bonemerang', 'bouncybubble', 'bulletpunch', 'buzzybuzz', 'circlethrow', 'clearsmog', 'doubleironbash', 'dragondarts', 'dragontail', 'endeavor', 'facade', 'firefang', 'flipturn', 'freezedry', 'frustration', 'geargrind', 'grassknot', 'gyroball', 'hex', 'icefang', 'iceshard', 'iciclespear', 'knockoff', 'lowkick', 'machpunch', 'nightshade', 'nuzzle', 'pikapapow', 'psychocut', 'pursuit', 'quickattack', 'rapidspin', 'return', 'rockblast', 'scorchingsands', 'seismictoss', 'shadowclaw', 'shadowsneak', 'sizzlyslide', 'storedpower', 'stormthrow', 'suckerpunch', 'superfang', 'surgingstrikes', 'tailslap', 'tripleaxel', 'uturn', 'veeveevolley', 'voltswitch', 'watershuriken', 'weatherball',
	] as ID[] as readonly ID[];
	static readonly BAD_STRONG_MOVES = [
		'beakblast', 'belch', 'burnup', 'crushclaw', 'doomdesire', 'dragonrush', 'dreameater', 'eggbomb', 'firepledge', 'flyingpress', 'futuresight', 'grasspledge', 'hyperbeam', 'hyperfang', 'hyperspacehole', 'jawlock', 'landswrath', 'lastresort', 'megakick', 'megapunch', 'mistyexplosion', 'muddywater', 'nightdaze', 'pollenpuff', 'rockclimb', 'selfdestruct', 'shelltrap', 'skyuppercut', 'slam', 'strength', 'submission', 'synchronoise', 'takedown', 'thrash', 'uproar', 'waterpledge',
	] as ID[] as readonly ID[];
	static readonly GOOD_DOUBLES_MOVES = [
		'allyswitch', 'bulldoze', 'coaching', 'electroweb', 'faketears', 'fling', 'followme', 'healpulse', 'helpinghand', 'junglehealing', 'lifedew', 'muddywater', 'pollenpuff', 'psychup', 'ragepowder', 'safeguard', 'skillswap', 'snipeshot', 'wideguard',
	] as ID[] as readonly ID[];
	getBaseResults() {
		if (!this.species) return this.getDefaultResults();
		const dex = this.dex;
		let species = dex.getSpecies(this.species);
		const format = this.format;
		const isHackmons = (format.includes('hackmons') || format.endsWith('bh'));
		const isSTABmons = (format.includes('stabmons') || format === 'staaabmons');
		const galarBornLegality = (format.includes('battlestadium') || format.startsWith('vgc') && this.dex.gen === 8);
		const isHoennGaiden = this.modFormat === 'gen3hoenngaiden' || this.modFormat.endsWith('hoenngaiden');

		const abilityid = this.set ? toID(this.set.ability) : '' as ID;
		const itemid = this.set ? toID(this.set.item) : '' as ID;

		let learnsetid = this.firstLearnsetid(species.id);
		let moves: string[] = [];
		let sketchMoves: string[] = [];
		let sketch = false;
		let gen = '' + dex.gen;
		while (learnsetid) {
			let learnset = BattleTeambuilderTable.learnsets[learnsetid];
			if (this.mod) {
				learnset = JSON.parse(JSON.stringify(learnset));
				const overrideLearnsets = BattleTeambuilderTable[this.mod].overrideLearnsets;
				if (overrideLearnsets[learnsetid]) {
					for (const moveid in overrideLearnsets[learnsetid]) learnset[moveid] = overrideLearnsets[learnsetid][moveid];
				}
			}
			if (this.formatType === 'letsgo') learnset = BattleTeambuilderTable['letsgo'].learnsets[learnsetid];
			if (this.formatType?.startsWith('dlc1')) learnset = BattleTeambuilderTable['gen8dlc1'].learnsets[learnsetid];
			if (learnset) {
				for (let moveid in learnset) {
					let learnsetEntry = learnset[moveid];
					/* if (requirePentagon && learnsetEntry.indexOf('p') < 0) {
						continue;
					} */
					if (galarBornLegality && !learnsetEntry.includes('g')) {
						continue;
					} else if (!learnsetEntry.includes(gen)) {
						continue;
					}
					if (this.dex.gen >= 8 && this.dex.getMove(moveid) && this.dex.getMove(moveid).isNonstandard === 'Past' && this.formatType !== 'natdex') continue;
					if (this.formatType?.startsWith('dlc1') && BattleTeambuilderTable['gen8dlc1']?.nonstandardMoves.includes(moveid)) continue;
					if (moves.includes(moveid)) continue;
					moves.push(moveid);
					if (moveid === 'sketch') sketch = true;
					if (moveid === 'hiddenpower') {
						moves.push(
							'hiddenpowerbug', 'hiddenpowerdark', 'hiddenpowerdragon', 'hiddenpowerelectric', 'hiddenpowerfighting', 'hiddenpowerfire', 'hiddenpowerflying', 'hiddenpowerghost', 'hiddenpowergrass', 'hiddenpowerground', 'hiddenpowerice', 'hiddenpowerpoison', 'hiddenpowerpsychic', 'hiddenpowerrock', 'hiddenpowersteel', 'hiddenpowerwater'
						);
					}
					if (isHoennGaiden && moveid === 'batonpass') {
						moves.push('batonpassgaiden');
						moves.splice(moves.indexOf('batonpass'), 1);
					}
				}
			}
			learnsetid = this.nextLearnsetid(learnsetid, species.id);
		}
		if (sketch || isHackmons) {
			if (isHackmons) moves = [];
			for (let id in BattleMovedex) {
				if (!format.startsWith('cap') && (id === 'paleowave' || id === 'shadowstrike')) continue;
				const move = dex.getMove(id);
				if (move.gen > dex.gen) continue;
				if (sketch) {
					if (move.isMax || move.isZ || move.isNonstandard) continue;
					sketchMoves.push(move.id);
				} else {
					if (!(dex.gen < 8 || this.formatType === 'natdex') && move.isZ) continue;
					if (typeof move.isMax === 'string') continue;
					if (move.isNonstandard === 'Past' && this.formatType !== 'natdex' && dex.gen === 8) continue;
					moves.push(move.id);
				}
			}
		}
		if (this.formatType === 'metronome') moves = ['metronome'];
		if (isSTABmons) {
			for (let id in BattleMovedex) {
				let types: string[] = [];
				let baseSpecies = dex.getSpecies(species.changesFrom || species.name);
				if (!species.battleOnly) types.push(...species.types);
				let prevo = species.prevo;
				while (prevo) {
					const prevoSpecies = dex.getSpecies(prevo);
					types.push(...prevoSpecies.types);
					prevo = prevoSpecies.prevo;
				}
				if (species.battleOnly) species = baseSpecies;
				const excludedForme = (s: Species) => ['Alola', 'Alola-Totem', 'Galar', 'Galar-Zen'].includes(s.forme);
				if (baseSpecies.otherFormes && !['Wormadam', 'Urshifu'].includes(baseSpecies.baseSpecies)) {
					if (!excludedForme(species)) types.push(...baseSpecies.types);
					for (const formeName of baseSpecies.otherFormes) {
						const forme = dex.getSpecies(formeName);
						if (!forme.battleOnly && !excludedForme(forme)) types.push(...forme.types);
					}
				}
				const move = Dex.getMove(id);
				if (!types.includes(move.type)) continue;
				if (moves.includes(move.id)) continue;
				if (move.gen > dex.gen) continue;
				if (move.isZ || move.isMax || move.isNonstandard) continue;
				moves.push(id);
			}
		}

		moves.sort();
		sketchMoves.sort();

		let usableMoves: SearchRow[] = [];
		let uselessMoves: SearchRow[] = [];
		for (const id of moves) {
			const isUsable = this.moveIsNotUseless(id as ID, species, abilityid, itemid, moves);
			if (isUsable) {
				if (!usableMoves.length) usableMoves.push(['header', "Moves"]);
				usableMoves.push(['move', id as ID]);
			} else {
				if (!uselessMoves.length) uselessMoves.push(['header', "Usually useless moves"]);
				uselessMoves.push(['move', id as ID]);
			}
		}
		if (sketchMoves.length) {
			usableMoves.push(['header', "Sketched moves"]);
			uselessMoves.push(['header', "Useless sketched moves"]);
		}
		for (const id of sketchMoves) {
			const isUsable = this.moveIsNotUseless(id as ID, species, abilityid, itemid, sketchMoves);
			if (isUsable) {
				usableMoves.push(['move', id as ID]);
			} else {
				uselessMoves.push(['move', id as ID]);
			}
		}
		return [...usableMoves, ...uselessMoves];
	}
	filter(row: SearchRow, filters: string[][]) {
		if (!filters) return true;
		if (row[0] !== 'move') return true;
		const move = this.dex.getMove(row[1]);
		for (const [filterType, value] of filters) {
			switch (filterType) {
			case 'type':
				if (move.type !== value) return false;
				break;
			case 'category':
				if (move.category !== value) return false;
				break;
			case 'pokemon':
				if (!this.canLearn(value as ID, move.id)) return false;
				break;
			}
		}
		return true;
	}
	sort(results: SearchRow[], sortCol: string): SearchRow[] {
		switch (sortCol) {
		case 'power':
			let powerTable: {[id: string]: number | undefined} = {
				return: 102, frustration: 102, spitup: 300, trumpcard: 200, naturalgift: 80, grassknot: 120,
				lowkick: 120, gyroball: 150, electroball: 150, flail: 200, reversal: 200, present: 120,
				wringout: 120, crushgrip: 120, heatcrash: 120, heavyslam: 120, fling: 130, magnitude: 150,
				beatup: 24, punishment: 1020, psywave: 1250, nightshade: 1200, seismictoss: 1200,
				dragonrage: 1140, sonicboom: 1120, superfang: 1350, endeavor: 1399, sheercold: 1501,
				fissure: 1500, horndrill: 1500, guillotine: 1500,
			};
			return results.sort(([rowType1, id1], [rowType2, id2]) => {
				const modPow1 = this.mod ? BattleTeambuilderTable[this.mod].overrideBP[id1] : null;
				const modPow2 = this.mod ? BattleTeambuilderTable[this.mod].overrideBP[id2] : null;
				let move1 = BattleMovedex[id1];
				let move2 = BattleMovedex[id2];
				let pow1 = modPow1 || move1.basePower || powerTable[id1] || (move1.category === 'Status' ? -1 : 1400);
				let pow2 = modPow2 || move2.basePower || powerTable[id2] || (move2.category === 'Status' ? -1 : 1400);
				return pow2 - pow1;
			});
		case 'accuracy':
			return results.sort(([rowType1, id1], [rowType2, id2]) => {
				const modAcc1 = this.mod ? BattleTeambuilderTable[this.mod].overrideAcc[id1] : null;
				const modAcc2 = this.mod ? BattleTeambuilderTable[this.mod].overrideAcc[id2] : null;
				let accuracy1 = modAcc1 || BattleMovedex[id1].accuracy || 0;
				let accuracy2 = modAcc2 || BattleMovedex[id2].accuracy || 0;
				if (accuracy1 === true) accuracy1 = 101;
				if (accuracy2 === true) accuracy2 = 101;
				return accuracy2 - accuracy1;
			});
		case 'pp':
			return results.sort(([rowType1, id1], [rowType2, id2]) => {
				const modPP1 = this.mod ? BattleTeambuilderTable[this.mod].overridePP[id1] : null;
				const modPP2 = this.mod ? BattleTeambuilderTable[this.mod].overridePP[id2] : null;
				let pp1 = modPP1 || BattleMovedex[id1].pp || 0;
				let pp2 = modPP2 || BattleMovedex[id2].pp || 0;
				return pp2 - pp1;
			});
		case 'name':
			return results.sort(([rowType1, id1], [rowType2, id2]) => {
				const name1 = id1;
				const name2 = id2;
				return name1 < name2 ? -1 : name1 > name2 ? 1 : 0;
			});
		}
		throw new Error("invalid sortcol");
	}
}

class BattleCategorySearch extends BattleTypedSearch<'category'> {
	getTable() {
		return {physical: 1, special: 1, status: 1};
	}
	getDefaultResults(): SearchRow[] {
		return [
			['category', 'physical' as ID],
			['category', 'special' as ID],
			['category', 'status' as ID],
		];
	}
	getBaseResults() {
		return this.getDefaultResults();
	}
	filter(row: SearchRow, filters: string[][]): boolean {
		throw new Error("invalid filter");
	}
	sort(results: SearchRow[], sortCol: string | null): SearchRow[] {
		throw new Error("invalid sortcol");
	}
}

class BattleTypeSearch extends BattleTypedSearch<'type'> {
	getTable() {
		if (!this.mod) return window.BattleTypeChart;
		else return {...BattleTeambuilderTable[this.mod].overrideTypeChart, ...window.BattleTypeChart};
	}
	getDefaultResults(): SearchRow[] {
		const results: SearchRow[] = [];
		for (let id in window.BattleTypeChart) {
			results.push(['type', id as ID]);
		}
		return results;
	}
	getBaseResults() {
		return this.getDefaultResults();
	}
	filter(row: SearchRow, filters: string[][]): boolean {
		throw new Error("invalid filter");
	}
	sort(results: SearchRow[], sortCol: string | null): SearchRow[] {
		throw new Error("invalid sortcol");
	}
}
