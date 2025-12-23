import { registerDynamicChunk } from 'blocksy-frontend'

registerDynamicChunk('blocksy_ext_woo_extra_scroll_to_ct', {
	mount: (el) => {
		const hash = window.location.hash

		if (hash) {
			const anchor = document.querySelector(`[href="${hash}"]`)
			const tab = document.querySelector(hash)

			if (anchor && tab) {
				anchor.click()
			}
		}
	},
})
