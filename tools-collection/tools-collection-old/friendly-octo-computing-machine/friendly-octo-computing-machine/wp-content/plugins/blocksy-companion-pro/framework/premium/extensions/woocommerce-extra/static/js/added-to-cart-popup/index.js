import { registerDynamicChunk, getCurrentScreen } from 'blocksy-frontend'
import { fetchAddToCartPopupContent } from './common'

const { settings } = ct_localizations.dynamic_js_chunks.find(
	({ id }) => id === 'blocksy_ext_woo_extra_added_to_cart_popup'
)

registerDynamicChunk('blocksy_ext_woo_extra_added_to_cart_popup', {
	mount: (
		el,
		{ event, eventData: fragments, eventArguments: [cart_hash, $button] }
	) => {
		if (!$button?.[0]) {
			return
		}

		const currentScreen = getCurrentScreen({ withTablet: true })

		if (!settings.visibility[currentScreen]) {
			return
		}

		if (
			($button[0].closest('.ct-product-add-to-cart') ||
				$button[0].closest('.ct-floating-bar-actions')) &&
			settings.template.single
		) {
			fetchAddToCartPopupContent(
				fragments['__SKIP__blocksy-added-to-cart-popup']
			)
		}

		if (
			($button[0].closest('.ct-woo-card-actions') ||
				$button[0].closest('.ct-woo-card-extra')) &&
			settings.template.archive
		) {
			fetchAddToCartPopupContent(
				fragments['__SKIP__blocksy-added-to-cart-popup']
			)
		}
	},
})
