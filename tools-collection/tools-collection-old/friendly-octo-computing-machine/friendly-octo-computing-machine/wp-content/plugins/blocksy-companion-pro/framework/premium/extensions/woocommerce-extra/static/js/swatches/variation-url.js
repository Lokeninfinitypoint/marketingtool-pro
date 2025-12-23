import { registerDynamicChunk } from 'blocksy-frontend'

const shareNetworks = {
	facebook: 'u',
	twitter: 'url',
	// pinterest: '',
	linkedin: 'url',
	reddit: 'url',
	hacker_news: 'u',
	vk: 'url',
	ok: 'st.shareUrl',
	telegram: 'url',
	viber: 'text',
	whatsapp: 'text',
	flipboard: 'url',
	email: 'body',
	line: 'url',
}

registerDynamicChunk('blocksy_ext_woo_extra_swatches_variation_url', {
	mount: (el, { event }) => {
		const select = el

		const urlParams = new URLSearchParams(window.location.search)
		urlParams.delete(select.name)

		const selectValue = String(select.value)

		if (selectValue) {
			urlParams.set(select.name, selectValue)
		} else {
			urlParams.delete(select.name)
		}

		if (!urlParams.toString()) {
			window.history.replaceState({}, '', window.location.pathname)
			return
		}

		window.history.replaceState(
			{},
			'',
			`${window.location.pathname}?${urlParams.toString()}`
		)

		const shareButtons = document.querySelectorAll('.ct-share-box a')

		shareButtons.forEach((button) => {
			const { network } = button.dataset

			if (network && shareNetworks?.[network]) {
				const url = new URL(button.href)

				if (url.searchParams.has(shareNetworks[network])) {
					url.searchParams.set(
						shareNetworks[network],
						window.location.href
					)
				}

				button.href = url.toString()
			}
		})
	},
})
