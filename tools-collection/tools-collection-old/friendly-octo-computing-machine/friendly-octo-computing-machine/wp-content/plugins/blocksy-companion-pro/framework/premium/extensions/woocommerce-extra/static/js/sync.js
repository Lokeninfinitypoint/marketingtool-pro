import './variables'

import { responsiveClassesFor } from 'blocksy-customizer-sync'

import { mountQuickViewSync } from './sync/quick-view'
import { mountCompareSync } from './sync/compare'
import { mountAddedToCartPopupSync } from './sync/added-to-cart-popup'
import { mountSuggestedProductsSync } from './sync/suggested-products'
import { mountFloatingBarSync } from './sync/floating-bar'
import { mountSizeGuideSync } from './sync/size-guide'
import { mountSizeWaitlistSync } from './sync/waitlist'
import { mountFiltersSync } from './sync/filters'
import { mountStockScarcitySync } from './sync/stock-scarcity'
import { mountSwatchesSync } from './sync/swatches'

mountQuickViewSync()
mountCompareSync()
mountAddedToCartPopupSync()
mountSuggestedProductsSync()
mountFloatingBarSync()
mountSizeGuideSync()
mountSizeWaitlistSync()
mountSwatchesSync()
mountFiltersSync()
mountStockScarcitySync()

wp.customize('woo_has_new_custom_badge_label', (val) =>
	val.bind((to) => {
		Array.from(document.querySelectorAll('.ct-woo-badge-new')).map((el) => {
			el.textContent = to
		})
	})
)

wp.customize('woo_has_featured_custom_badge_label', (val) =>
	val.bind((to) => {
		Array.from(document.querySelectorAll('.ct-woo-badge-featured')).map(
			(el) => {
				el.textContent = to
			}
		)
	})
)

wp.customize('has_product_slider_arrows', (val) =>
	val.bind((to) => {
		responsiveClassesFor(
			'has_product_slider_arrows',
			document.querySelector('.flexy > .flexy-arrow-prev')
		)

		responsiveClassesFor(
			'has_product_slider_arrows',
			document.querySelector('.flexy > .flexy-arrow-next')
		)
	})
)

wp.customize('has_product_pills_arrows', (val) =>
	val.bind((to) => {
		responsiveClassesFor(
			'has_product_pills_arrows',
			document.querySelector('.flexy-pills > .flexy-arrow-prev')
		)

		responsiveClassesFor(
			'has_product_pills_arrows',
			document.querySelector('.flexy-pills > .flexy-arrow-next')
		)
	})
)

wp.customize('single_page_share_box_visibility', (val) => {
	val.bind((to) => {
		responsiveClassesFor(
			'single_page_share_box_visibility',
			document.querySelector('[data-prefix="single_page"] .ct-share-box')
		)
	})
})
