import {
	maybePromoteScalarValueIntoResponsive,
	getOptionFor,
} from 'blocksy-customizer-sync'
import ctEvents from 'ct-events'

export const collectVariablesForRelatedSlideshow = () => ({
	woocommerce_related_products_slideshow_columns: () => {
		const columns = getOptionFor(
			'woocommerce_related_products_slideshow_columns'
		)

		const selector = ':is(.related, .upsells)'

		const dynamicHeightSelectors = {
			desktop: '',
			tablet: '',
			mobile: '',
		}

		Object.keys(dynamicHeightSelectors).forEach((device) => {
			dynamicHeightSelectors[device] = [
				`${selector} [data-flexy="no"] [data-products]`,
				`.flexy-item:nth-child(n + ${parseFloat(columns[device]) + 1})`,
			].join(' ')
		})

		return [
			{
				selector: '.related [data-products], .upsells [data-products]',
				variable: 'grid-columns-width',
				responsive: true,
				extractValue: (val) => {
					const responsive =
						maybePromoteScalarValueIntoResponsive(val)

					ctEvents.trigger('blocksy:frontend:init')

					setTimeout(() => {
						const sliders = document.querySelectorAll(
							'.related .flexy-container, .upsells .flexy-container'
						)

						if (sliders.length) {
							sliders.forEach((slider) => {
								const firstChild = slider.querySelector(
									'.flexy-item:first-child'
								)

								if (slider.flexy) {
									slider.flexy.scheduleSliderRecalculation()
								}
							})
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
					`${selector} [data-flexy="no"] [data-products] .flexy-item:nth-child`,
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
})
