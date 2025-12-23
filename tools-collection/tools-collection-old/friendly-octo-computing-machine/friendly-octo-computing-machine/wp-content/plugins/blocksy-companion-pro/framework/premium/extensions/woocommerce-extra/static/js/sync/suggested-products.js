import {
	responsiveClassesFor,
	typographyOption,
	maybePromoteScalarValueIntoResponsive,
	setRatioFor,
	getOptionFor,
} from 'blocksy-customizer-sync'
import ctEvents from 'ct-events'

const handleSuggestedProducts = (prefix, container) => {
	if (!container) {
		return
	}

	responsiveClassesFor(`${prefix}products_visibility`, container)

	const flexyItems = container.querySelector('.flexy-items')

	const productsType = getOptionFor(`${prefix}products_type`)
	const imageRatio = getOptionFor(`${prefix}products_image_ratio`)

	if (flexyItems) {
		flexyItems.dataset.products = productsType
		;[...flexyItems.querySelectorAll('.ct-media-container')].map(
			(image) => {
				setRatioFor({
					ratio: imageRatio,
					el: image,
				})
			}
		)
	}

	setTimeout(() => {
		if (container.flexy) {
			container.flexy.scheduleSliderRecalculation()
		}
	}, 50)
}

export const mountSuggestedProductsSync = () => {
	wp.customize.bind('change', (e) => {
		if (!e.id.indexOf('suggested_products')) {
			return
		}

		const selectors = {
			mini_cart_suggested_: '.ct-suggested-products--mini-cart',
			checkout_suggested_: '.ct-suggested-products--checkout',
			cart_popup_suggested_: '.ct-suggested-products--cart-popup',
			cart_suggested_: '.ct-suggested-products--cart',
		}

		Object.keys(selectors).forEach((prefix) => {
			const container = document.querySelector(selectors[prefix])

			if (container) {
				handleSuggestedProducts(prefix, container)
			}
		})
	})
}

const handleArrowsVisibility = (selector, val) => {
	const container = document.querySelector(selector)
	if (!container) return

	const arrows = container.querySelector('.ct-slider-arrows')
	const products = container.querySelectorAll('.flexy-item')
	const productsCount = products.length

	if (!arrows || productsCount === 0) return

	const hiddenClasses = {
		desktop: 'ct-hidden-lg',
		tablet: 'ct-hidden-md',
		mobile: 'ct-hidden-sm',
	}

	arrows.classList.remove(...Object.values(hiddenClasses))

	for (const key in hiddenClasses) {
		if (val[key] >= productsCount) {
			arrows.classList.add(hiddenClasses[key])
		}
	}
}

export const collectVariablesForSuggestedProducts = () => {
	const prefixes = [
		'cart_popup_suggested_',
		'mini_cart_suggested_',
		'checkout_suggested_',
		'cart_suggested_',
	]

	const selectors = {
		mini_cart_suggested_: '.ct-suggested-products--mini-cart',
		checkout_suggested_: '.ct-suggested-products--checkout',
		cart_popup_suggested_: '.ct-suggested-products--cart-popup',
		cart_suggested_: '.ct-suggested-products--cart',
	}

	const result = prefixes.reduce((acc, prefix) => {
		const selector = selectors[prefix]

		return {
			...acc,
			[`${prefix}products_columns`]: () => {
				const columns = maybePromoteScalarValueIntoResponsive(
					getOptionFor(`${prefix}products_columns`)
				)

				const dynamicHeightSelectors = {
					desktop: '',
					tablet: '',
					mobile: '',
				}

				Object.keys(dynamicHeightSelectors).forEach((device) => {
					dynamicHeightSelectors[device] = [
						`${selector}[data-flexy*="no"]`,
						`.flexy-item:nth-child(n + ${
							parseFloat(columns[device]) + 1
						})`,
					].join(' ')
				})

				return [
					{
						selector,
						variable: 'grid-columns-width',
						responsive: true,
						extractValue: (val) => {
							const responsive =
								maybePromoteScalarValueIntoResponsive(val)

							handleArrowsVisibility(selector, responsive)

							ctEvents.trigger('blocksy:frontend:init')

							setTimeout(() => {
								const maybeSlider =
									document.querySelector(selector)

								if (!maybeSlider) {
									return
								}

								if (maybeSlider.flexy) {
									maybeSlider.flexy.scheduleSliderRecalculation()
								}
							}, 50)

							return {
								desktop: `calc(100% / ${responsive.desktop})`,
								tablet: `calc(100% / ${responsive.tablet})`,
								mobile: `calc(100% / ${responsive.mobile})`,
							}
						},
					},

					{
						selector: dynamicHeightSelectors,
						variable: 'height',
						responsive: true,
						variableType: 'property',
						dropSelectors: [
							// Drop all related CSS before we add new rules to
							// avoid conflicts
							`${selector}[data-flexy*="no"] .flexy-item:nth-child`,
						],

						extractValue: (val) => {
							return {
								desktop: '1px',
								tablet: '1px',
								mobile: '1px',
							}
						},
					},
				]
			},

			[`${prefix}products_image_width`]: {
				selector,
				variable: 'product-image-width',
				responsive: true,
				unit: '',
			},

			...typographyOption({
				id: `${prefix}products_title_font`,
				selector: `${selector} [data-products] .ct-product-title`,
			}),

			[`${prefix}products_title_color`]: [
				{
					selector: `${selector} [data-products] .ct-product-title`,
					variable: 'theme-link-initial-color',
					type: 'color:default',
					responsive: true,
				},

				{
					selector: `${selector} [data-products] .ct-product-title`,
					variable: 'theme-link-hover-color',
					type: 'color:hover',
					responsive: true,
				},
			],

			...typographyOption({
				id: `${prefix}products_price_font`,
				selector: `${selector} [data-products] .price`,
			}),

			[`${prefix}products_price_color`]: {
				selector: `${selector} [data-products] .price`,
				variable: 'theme-text-color',
				type: 'color',
			},

			[`${prefix}products_image_radius`]: {
				selector: `${selector}`,
				type: 'spacing',
				variable: 'theme-border-radius',
				emptyValue: 3,
				responsive: true,
			},
		}
	}, {})

	return result
}
