import {
	responsiveClassesFor,
	withKeys,
	maybePromoteScalarValueIntoResponsive,
	setRatioFor,
} from 'blocksy-customizer-sync'

export const mountCompareSync = () => {
	wp.customize('product_compare_layout', (val) => {
		val.bind((to) => {
			to.forEach((layout) => {
				if (layout['id'] === 'product_main') {
					const images = document.querySelectorAll(
						'.ct-compare-column .thumb_class'
					)

					images.forEach((el) => {
						setRatioFor({
							ratio: layout['compare_image_ratio'],
							el,
						})
					})
				}
			})
		})
	})

	wp.customize('product_compare_bar_button_label', (val) => {
		val.bind((to) => {
			const compareButton = document.querySelector(
				'.ct-compare-bar .ct-button'
			)

			if (compareButton) {
				const maybeIcon = compareButton.querySelector('svg')

				compareButton.innerHTML = `${maybeIcon.outerHTML || ''}${to}`
			}
		})
	})

	wp.customize('product_compare_bar_visibility', (val) => {
		val.bind((to) => {
			const compareBar = document.querySelector('.ct-compare-bar')

			if (compareBar) {
				responsiveClassesFor(
					'product_compare_bar_visibility',
					compareBar
				)
			}
		})
	})
}

export const collectVariablesForCompareLayers = (v) => {
	let variables = []
	v.map((layer) => {
		let selectorsMap = {
			product_brands: '.ct-compare-column > .ct-product-brands',
		}

		if (layer['id'] === 'product_brands') {
			variables = [
				...variables,

				{
					selector: selectorsMap[layer.id],
					variable: 'product-brand-logo-size',
					responsive: true,
					unit: 'px',
					extractValue: () => {
						return layer.brand_logo_size || 60
					},
				},

				{
					selector: selectorsMap[layer.id],
					variable: 'product-brands-gap',
					responsive: true,
					unit: 'px',
					extractValue: () => {
						return layer.brand_logo_gap || 10
					},
				},
			]
		}
	})

	return variables
}

export const collectVariablesForCompare = () => ({
	compare_modal_shadow: {
		selector: '#ct-compare-modal',
		type: 'box-shadow',
		variable: 'theme-box-shadow',
		responsive: true,
	},

	compare_modal_radius: {
		selector: '#ct-compare-modal',
		type: 'spacing',
		variable: 'theme-border-radius',
		responsive: true,
	},

	compare_modal_background: {
		selector: '#ct-compare-modal',
		variable: 'modal-background-color',
		type: 'color',
		responsive: true,
	},

	compare_modal_backdrop: {
		selector: '#ct-compare-modal',
		variable: 'modal-backdrop-color',
		type: 'color',
		responsive: true,
	},

	// compare bar
	...withKeys(
		['product_compare_bar_visibility', 'product_compare_bar_height'],
		{
			selector: '.ct-drawer-canvas[data-compare-bar]',
			variable: 'compare-bar-height',
			responsive: true,
			unit: 'px',
			extractValue: (el) => {
				const product_compare_bar_height = JSON.parse(
					JSON.stringify(
						maybePromoteScalarValueIntoResponsive(
							wp.customize('product_compare_bar_height')()
						)
					)
				)

				let product_compare_bar_visibility = wp.customize(
					'product_compare_bar_visibility'
				)()

				if (!product_compare_bar_visibility.desktop) {
					product_compare_bar_height.desktop = 0
				}

				if (!product_compare_bar_visibility.tablet) {
					product_compare_bar_height.tablet = 0
				}

				if (!product_compare_bar_visibility.mobile) {
					product_compare_bar_height.mobile = 0
				}

				return product_compare_bar_height
			},
		}
	),

	product_compare_bar_button_font_color: [
		{
			selector: '.ct-compare-bar',
			variable: 'theme-button-text-initial-color',
			type: 'color:default',
			responsive: true,
		},

		{
			selector: '.ct-compare-bar',
			variable: 'theme-button-text-hover-color',
			type: 'color:hover',
			responsive: true,
		},
	],

	product_compare_bar_button_background_color: [
		{
			selector: '.ct-compare-bar',
			variable: 'theme-button-background-initial-color',
			type: 'color:default',
			responsive: true,
		},

		{
			selector: '.ct-compare-bar',
			variable: 'theme-button-background-hover-color',
			type: 'color:hover',
			responsive: true,
		},
	],

	product_compare_bar_background: {
		selector: '.ct-compare-bar',
		variable: 'compare-bar-background-color',
		type: 'color',
		responsive: true,
	},
})
