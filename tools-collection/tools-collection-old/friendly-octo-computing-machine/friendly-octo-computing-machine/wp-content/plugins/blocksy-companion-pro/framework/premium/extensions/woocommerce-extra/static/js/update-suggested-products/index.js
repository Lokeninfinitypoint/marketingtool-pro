import $ from 'jquery'
import { registerDynamicChunk } from 'blocksy-frontend'

registerDynamicChunk('blocksy_ext_woo_extra_suggested_products', {
	mount: (el, { event, eventData, eventArguments }) => {
		const body = new FormData()

		body.append('action', 'blocksy_update_suggested')

		fetch(ct_localizations.ajax_url, {
			method: 'POST',
			body,
		})
			.then((r) => r.json())
			.then(({ success, data: { content } }) => {
				if (!success) {
					return
				}

				const container = document.querySelector(
					'.ct-suggested-products--checkout'
				)

				if (container) {
					container.outerHTML = content

					ctEvents.trigger('blocksy:frontend:init')

					if (event.type === 'added_to_cart') {
						$(document.body).trigger('update_checkout')
					}
				}
			})
	},
})
