import { addAction } from '@wordpress/hooks'

import { handleBackgroundOptionFor, setRatioFor } from 'blocksy-customizer-sync'
import ctEvents from 'ct-events'

const quickViewData = {
	navPrev: '',
	navNext: '',
}

const render = (container = document) => {
	let content = container.querySelector('.ct-quick-view-card')

	if (!content) {
		return
	}

	content = content.closest('.ct-panel-content')
	;[
		...container.querySelectorAll(
			'.ct-quick-view-content .ct-media-container'
		),
	].map((el) => {
		setRatioFor({
			ratio: wp.customize('woocommerce_quickview_gallery_ratio')(),
			el,
		})
	})

	const woocommerce_quickview_navigation = wp.customize(
		'woocommerce_quickview_navigation'
	)()

	if (woocommerce_quickview_navigation !== 'yes') {
		;[
			...container.querySelectorAll(
				'.ct-quick-view-nav-prev, .ct-quick-view-nav-next'
			),
		].map((el) => {
			el.remove()
		})

		content.removeAttribute('data-arrows')
	} else {
		if (!container.querySelector('.ct-quick-view-nav-next')) {
			content.insertAdjacentHTML('afterbegin', quickViewData.navPrev)
			content.insertAdjacentHTML('beforeend', quickViewData.navNext)
		}

		content.dataset.arrows = ''
	}

	ctEvents.trigger('blocksy:frontend:init')

	if (window.wcpaInit) {
		window.wcpaInit()
	}
}

export const mountQuickViewSync = () => {
	addAction(
		'ct.quick-view.insert-content',
		'blocksy-companion',
		(div, { data, productId }) => {
			quickViewData.navPrev = div.querySelector(
				'.ct-quick-view-nav-prev'
			).outerHTML

			quickViewData.navNext = div.querySelector(
				'.ct-quick-view-nav-next'
			).outerHTML

			render(div)
		}
	)

	wp.customize('woocommerce_quickview_gallery_ratio', (val) =>
		val.bind((to) => {
			render()
		})
	)

	wp.customize('woocommerce_quickview_navigation', (val) =>
		val.bind((to) => {
			render()
		})
	)
}

export const collectVariablesForQuickView = () => ({
	woocommerce_quick_view_width: {
		selector: '.ct-quick-view-card',
		variable: 'theme-normal-container-max-width',
		responsive: true,
		unit: 'px',
	},

	quick_view_title_color: {
		selector: '.ct-quick-view-card .entry-summary .product_title',
		variable: 'theme-heading-color',
		type: 'color',
	},

	quick_view_price_color: {
		selector: '.ct-quick-view-card .entry-summary .price',
		variable: 'theme-text-color',
		type: 'color',
	},

	quick_view_description_color: {
		selector:
			'.ct-quick-view-card .woocommerce-product-details__short-description',
		variable: 'theme-text-color',
		type: 'color',
	},

	quick_view_add_to_cart_text: [
		{
			selector:
				'.ct-quick-view-card .entry-summary .single_add_to_cart_button',
			variable: 'theme-button-text-initial-color',
			type: 'color:default',
		},

		{
			selector:
				'.ct-quick-view-card .entry-summary .single_add_to_cart_button',
			variable: 'theme-button-text-hover-color',
			type: 'color:hover',
		},
	],

	quick_view_add_to_cart_background: [
		{
			selector:
				'.ct-quick-view-card .entry-summary .single_add_to_cart_button',
			variable: 'theme-button-background-initial-color',
			type: 'color:default',
		},

		{
			selector:
				'.ct-quick-view-card .entry-summary .single_add_to_cart_button',
			variable: 'theme-button-background-hover-color',
			type: 'color:hover',
		},
	],

	quick_view_view_cart_button_text: [
		{
			selector:
				'.ct-quick-view-card .entry-summary .ct-cart-actions .added_to_cart',
			variable: 'theme-button-text-initial-color',
			type: 'color:default',
		},

		{
			selector:
				'.ct-quick-view-card .entry-summary .ct-cart-actions .added_to_cart',
			variable: 'theme-button-text-hover-color',
			type: 'color:hover',
		},
	],

	quick_view_view_cart_button_background: [
		{
			selector:
				'.ct-quick-view-card .entry-summary .ct-cart-actions .added_to_cart',
			variable: 'theme-button-background-initial-color',
			type: 'color:default',
		},

		{
			selector:
				'.ct-quick-view-card .entry-summary .ct-cart-actions .added_to_cart',
			variable: 'theme-button-background-hover-color',
			type: 'color:hover',
		},
	],

	quick_view_product_page_button_text: [
		{
			selector: '.ct-quick-view-card .entry-summary .ct-quick-more',
			variable: 'theme-button-text-initial-color',
			type: 'color:default',
		},

		{
			selector: '.ct-quick-view-card .entry-summary .ct-quick-more',
			variable: 'theme-button-text-hover-color',
			type: 'color:hover',
		},
	],

	quick_view_product_page_button_background: [
		{
			selector: '.ct-quick-view-card .entry-summary .ct-quick-more',
			variable: 'theme-button-background-initial-color',
			type: 'color:default',
		},

		{
			selector: '.ct-quick-view-card .entry-summary .ct-quick-more',
			variable: 'theme-button-background-hover-color',
			type: 'color:hover',
		},
	],

	quick_view_shadow: {
		selector: '.ct-quick-view-card',
		type: 'box-shadow',
		variable: 'theme-box-shadow',
	},

	quick_view_radius: {
		selector: '.ct-quick-view-card',
		type: 'spacing',
		variable: 'theme-border-radius',
	},

	...handleBackgroundOptionFor({
		id: 'quick_view_background',
		selector: '.ct-quick-view-card',
	}),

	...handleBackgroundOptionFor({
		id: 'quick_view_backdrop',
		selector: '.quick-view-modal',
	}),
})
