import { registerDynamicChunk, getCurrentScreen } from 'blocksy-frontend'
import ctEvents from 'ct-events'
import cachedFetch, {
	cachedFetchGetCacheState,
	cachedFetchInvalidateCache,
	CACHE_STATE,
} from 'ct-wordpress-helpers/cached-fetch'

export const afterRequest = (panel, e) => {
	ctEvents.trigger('blocksy:frontend:init')

	setTimeout(() => {
		setTimeout(() => {
			ctEvents.trigger('ct:overlay:handle-click', {
				e,
				href: `#${panel.id}`,
				options: {
					openStrategy: 'skip',
					isModal: true,
					computeScrollContainer: () => {
						if (!panel.closest('body')) {
							return
						}

						return panel.querySelector('.ct-compare-table')
					},
					clickOutside: true,
					focus: false,
				},
			})
		})
	})
}

export const getFetchDescriptorFor = () => {
	const searchParams = new URLSearchParams(location.search)

	searchParams.append('action', 'blocksy_get_woo_compare')
	searchParams.append(
		'items',
		JSON.stringify(ct_localizations.blc_ext_compare_list.list)
	)

	const fetchUrl = `${ct_localizations.ajax_url}?${searchParams.toString()}`

	if (window.wp && window.wp.customize) {
		searchParams.append('wp_customize', 'on')
		cachedFetchInvalidateCache(fetchUrl)
	}

	const cacheState = cachedFetchGetCacheState(fetchUrl)

	return {
		isCached: cacheState === CACHE_STATE.LOADED,
		fetch: () =>
			new Promise((r) => {
				const fetchModal = () => {
					cachedFetch(fetchUrl)
						.then((r) => r.json())
						.then(({ success, data }) => {
							const div = document.createElement('div')
							div.innerHTML = data.content

							r(div.firstElementChild)
						})
				}

				if (window.blocksyCustomizerSync) {
					window.blocksyCustomizerSync.triggerCustomizerAutosave(
						fetchModal
					)
				} else {
					fetchModal()
				}
			}),
	}
}

const openCompareModal = ({ event, completeAction, el }) => {
	event.preventDefault()

	const fetchDescriptor = getFetchDescriptorFor()

	fetchDescriptor.fetch().then((content) => {
		document.querySelector('.ct-drawer-canvas').appendChild(content)
		const panel = document.querySelector('#ct-compare-modal')

		ctEvents.trigger('ct:overlay:handle-click', {
			event,
			options: {
				openStrategy: 'fast',
				container: panel,
			},
		})

		completeAction({
			finalState: '',
		})

		ctEvents.trigger('blocksy:wishlist:sync')

		afterRequest(panel, event)
	})
}

registerDynamicChunk('blocksy_ext_woo_extra_compare_modal', {
	mount: (el, { event, completeAction }) => {
		event.preventDefault()
		event.stopPropagation()

		openCompareModal({ event, completeAction, el })
	},

	maybeGetPanelContent: (el, { event }) => {
		const fetchDescriptor = getFetchDescriptorFor(el)

		if (fetchDescriptor.isCached) {
			return new Promise((resolve) => {
				// TODO: maybe implement actual content retrieval if will be
				// needed in the future.
				resolve('')
			})
		}

		return null
	},
})

ctEvents.on('ct:modal:closed', (modalContainer) => {
	if (modalContainer.id !== 'ct-compare-modal') {
		return
	}

	setTimeout(() => {
		modalContainer.remove()
	})
})
