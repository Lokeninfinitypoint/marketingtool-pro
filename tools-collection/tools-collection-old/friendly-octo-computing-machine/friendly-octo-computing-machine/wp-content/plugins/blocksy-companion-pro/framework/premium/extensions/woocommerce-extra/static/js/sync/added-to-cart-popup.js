import {
	withKeys,
	handleBackgroundOptionFor,
	typographyOption,
	setRatioFor,
	getOptionFor,
} from 'blocksy-customizer-sync'
import ctEvents from 'ct-events'

document.addEventListener('DOMContentLoaded', () => {
	if (
		!window.wc_add_to_cart_params ||
		!window.wc_add_to_cart_params.wc_ajax_url
	) {
		return
	}

	window.wc_add_to_cart_params.wc_ajax_url += '&wp_customize=on'
})

const elements = {
	added_to_cart_popup_show_price: '.ct-added-to-cart-product .price',
	added_to_cart_popup_show_description:
		'.ct-added-to-cart-product .ct-product-description',
	added_to_cart_popup_show_attributes:
		'.ct-added-to-cart-product .ct-product-attributes',
	added_to_cart_popup_show_shipping:
		'.ct-added-to-cart-product .ct-added-to-cart-popup-shipping',
	added_to_cart_popup_show_tax:
		'.ct-added-to-cart-product .ct-added-to-cart-popup-tax',
	added_to_cart_popup_show_total:
		'.ct-added-to-cart-product .ct-added-to-cart-popup-total',
	added_to_cart_popup_show_cart: '.ct-added-to-cart-popup-cart',
	added_to_cart_popup_show_continue: '.ct-added-to-cart-popup-continue',
	added_to_cart_popup_show_checkout: '.ct-added-to-cart-popup-checkout',
}

const handleTotalsVisibility = (popup) => {
	const show_shipping = getOptionFor('added_to_cart_popup_show_shipping')
	const show_tax = getOptionFor('added_to_cart_popup_show_tax')
	const show_total = getOptionFor('added_to_cart_popup_show_total')

	const totals = popup.querySelector('.ct-product-totals')

	if (!totals) {
		return
	}

	totals.hidden =
		show_shipping === 'no' && show_tax === 'no' && show_total === 'no'
}

const handleActionsVisibility = (popup) => {
	const show_cart = getOptionFor('added_to_cart_popup_show_cart')
	const show_continue = getOptionFor('added_to_cart_popup_show_continue')
	const show_checkout = getOptionFor('added_to_cart_popup_show_checkout')

	const actions = popup.querySelector('.ct-popup-actions')

	if (!actions) {
		return
	}

	const hideActions =
		show_cart === 'no' && show_continue === 'no' && show_checkout === 'no'

	if (hideActions) {
		actions.style.display = 'none'
	} else {
		actions.removeAttribute('style')
	}
}

const handleImage = (popup) => {
	const ratio = getOptionFor('added_to_cart_popup_image_ratio')

	const el = popup.querySelector(
		'.ct-added-to-cart-product .ct-media-container'
	)
	const showImage = getOptionFor('added_to_cart_popup_show_image')

	const imageContainer = popup.querySelector('.ct-added-to-cart-product')

	if (!el) {
		return
	}

	if (showImage === 'yes') {
		el.removeAttribute('style')
		imageContainer.classList.remove('no-image')
	} else {
		el.style.display = 'none'
		imageContainer.classList.add('no-image')
	}

	setRatioFor({
		ratio,
		el,
	})
}

const renderAddedToCartPopup = (popup = null) => {
	if (!popup) {
		popup = document.querySelector('#ct-added-to-cart-popup')
	}

	if (!popup) {
		return
	}

	const position = getOptionFor('added_to_cart_popup_position')
	const size = getOptionFor('added_to_cart_popup_size')

	Object.keys(elements).map((key) => {
		const el = popup.querySelector(elements[key])

		if (!el) {
			return
		}

		const value = getOptionFor(key)

		if (value === 'no') {
			el.style.display = 'none'
		} else {
			el.removeAttribute('style')
		}
	})

	popup.dataset.popupPosition = position
	popup.dataset.popupSize = size

	handleTotalsVisibility(popup)
	handleActionsVisibility(popup)
	handleImage(popup)
}

export const mountAddedToCartPopupSync = () => {
	ctEvents.on('ct:sync:added-to-cart-popup', (data) => {
		renderAddedToCartPopup(data.popup)
	})

	wp.customize.bind('change', (e) => {
		if (e.id.indexOf('added_to_cart_popup') !== 0) {
			return
		}

		renderAddedToCartPopup()
	})
}

export const collectVariablesForAddedToCartPopup = () => ({
	added_to_cart_popup_image_width: {
		selector: '#ct-added-to-cart-popup',
		variable: 'product-image-width',
		unit: '%',
	},

	...typographyOption({
		id: 'added_to_cart_popup_title_font',
		selector: '.ct-added-to-cart-product .woocommerce-loop-product__title',
	}),

	added_to_cart_popup_title_color: {
		selector: '.ct-added-to-cart-product .woocommerce-loop-product__title',
		variable: 'theme-heading-color',
		type: 'color',
	},

	...typographyOption({
		id: 'added_to_cart_popup_price_font',
		selector: '.ct-added-to-cart-product .price',
	}),

	added_to_cart_popup_price_color: {
		selector: '.ct-added-to-cart-product .price',
		variable: 'theme-text-color',
		type: 'color',
	},

	added_to_cart_popup_image_radius: {
		selector: '.ct-added-to-cart-product .ct-media-container',
		type: 'spacing',
		variable: 'theme-border-radius',
		emptyValue: 3,
		responsive: true,
	},

	added_to_cart_popup_entrance_speed: {
		selector: '#ct-added-to-cart-popup',
		variable: 'popup-entrance-speed',
		unit: 's',
	},

	added_to_cart_popup_entrance_value: {
		selector: '#ct-added-to-cart-popup',
		variable: 'popup-entrance-value',
		unit: 'px',
	},

	...withKeys(
		[
			'added_to_cart_popup_size',
			'added_to_cart_popup_max_width',
			'added_to_cart_popup_max_height',
		],
		[
			{
				selector: '#ct-added-to-cart-popup[data-popup-size="custom"]',
				variable: 'popup-max-width',
				responsive: true,
				unit: '',
				extractValue: () => {
					let added_to_cart_popup_size =
						wp.customize('added_to_cart_popup_size')() || 'large'

					if (added_to_cart_popup_size !== 'custom') {
						return 'CT_CSS_SKIP_RULE'
					}

					let added_to_cart_popup_max_width = wp.customize(
						'added_to_cart_popup_max_width'
					)()

					return added_to_cart_popup_max_width
				},
			},

			{
				selector: '#ct-added-to-cart-popup[data-popup-size="custom"]',
				variable: 'popup-max-height',
				responsive: true,
				unit: '',
				extractValue: () => {
					let added_to_cart_popup_size =
						wp.customize('added_to_cart_popup_size')() || 'large'

					if (added_to_cart_popup_size !== 'custom') {
						return 'CT_CSS_SKIP_RULE'
					}

					let added_to_cart_popup_max_height = wp.customize(
						'added_to_cart_popup_max_height'
					)()

					return added_to_cart_popup_max_height
				},
			},

			{
				selector: '#ct-added-to-cart-popup[data-popup-size="custom"]',
				variable: 'popup-height',
				responsive: true,
				unit: '',
				extractValue: () => {
					let added_to_cart_popup_size =
						wp.customize('added_to_cart_popup_size')() || 'large'

					if (added_to_cart_popup_size !== 'custom') {
						return 'CT_CSS_SKIP_RULE'
					}

					return '100%'
				},
			},
		]
	),

	...handleBackgroundOptionFor({
		id: 'added_to_cart_popup_background',
		selector: '#ct-added-to-cart-popup .ct-popup-inner > article',
		responsive: true,
	}),

	...handleBackgroundOptionFor({
		id: 'added_to_cart_popup_backdrop_background',
		selector: '#ct-added-to-cart-popup',
		responsive: true,
	}),

	added_to_cart_popup_edges_offset: {
		selector: '#ct-added-to-cart-popup',
		variable: 'popup-edges-offset',
		responsive: true,
		unit: 'px',
	},

	added_to_cart_popup_padding: {
		selector: '#ct-added-to-cart-popup',
		type: 'spacing',
		variable: 'popup-padding',
		responsive: true,
	},

	added_to_cart_popup_border_radius: {
		selector: '#ct-added-to-cart-popup',
		type: 'spacing',
		variable: 'popup-border-radius',
		responsive: true,
	},

	added_to_cart_popup_shadow: {
		selector: '#ct-added-to-cart-popup',
		type: 'box-shadow',
		variable: 'popup-box-shadow',
		responsive: true,
	},

	added_to_cart_popup_close_button_icon_size: {
		selector: '#ct-added-to-cart-popup .ct-toggle-close',
		variable: 'theme-icon-size',
		unit: 'px',
	},

	added_to_cart_popup_close_button_color: [
		{
			selector: '#ct-added-to-cart-popup .ct-toggle-close',
			variable: 'theme-icon-color',
			type: 'color:default',
			responsive: true,
		},

		{
			selector: '#ct-added-to-cart-popup .ct-toggle-close:hover',
			variable: 'theme-icon-color',
			type: 'color:hover',
			responsive: true,
		},
	],
})
