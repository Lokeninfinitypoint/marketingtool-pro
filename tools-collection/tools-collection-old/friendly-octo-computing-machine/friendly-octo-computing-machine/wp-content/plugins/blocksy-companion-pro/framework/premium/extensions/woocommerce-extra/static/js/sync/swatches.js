export const mountSwatchesSync = () => {
	wp.customize('out_of_stock_swatch_type', (value) => {
		value.bind((to) => {
			const forms = document.querySelectorAll('.variations_form')

			if (forms && forms.length) {
				forms.forEach((el) => {
					el.dataset.outOfStockSwatchType = to
				})
			}
		})
	})

	wp.customize('color_swatch_shape', (value) => {
		value.bind((to) => {
			const swatches = document.querySelectorAll(
				'[data-swatches-type="color"]'
			)

			if (swatches && swatches.length) {
				swatches.forEach((el) => {
					el.dataset.swatchesShape = to
				})
			}
		})
	})

	wp.customize('image_swatch_shape', (value) => {
		value.bind((to) => {
			const swatches = document.querySelectorAll(
				'[data-swatches-type="image"]'
			)

			if (swatches && swatches.length) {
				swatches.forEach((el) => {
					el.dataset.swatchesShape = to
				})
			}
		})
	})

	wp.customize('button_swatch_shape', (value) => {
		value.bind((to) => {
			const swatches = document.querySelectorAll(
				'[data-swatches-type="button"]'
			)

			if (swatches && swatches.length) {
				swatches.forEach((el) => {
					el.dataset.swatchesShape = to
				})
			}
		})
	})

	wp.customize('mixed_swatch_shape', (value) => {
		value.bind((to) => {
			const swatches = document.querySelectorAll(
				'[data-swatches-type="mixed"]'
			)

			if (swatches && swatches.length) {
				swatches.forEach((el) => {
					el.dataset.swatchesShape = to
				})
			}
		})
	})

	wp.customize('variations_swatches_display_type', (val) => {
		val.bind((to) => {
			const swatches = document.querySelectorAll(
				'.single-product .product'
			)

			if (swatches && swatches.length) {
				swatches.forEach((el) => {
					if (to === 'yes') {
						el.classList.add('ct-inline-variations')
					} else {
						el.classList.remove('ct-inline-variations')
					}
				})
			}
		})
	})
}

export const collectVariablesForSwatches = () => ({
	archive_color_swatch_size: {
		selector: '.ct-card-variation-swatches [data-swatches-type="color"]',
		variable: 'swatch-size',
		responsive: true,
		unit: 'px',
	},

	single_color_swatch_size: {
		selector: '.variations_form.cart [data-swatches-type="color"]',
		variable: 'swatch-size',
		responsive: true,
		unit: 'px',
	},

	filter_widget_color_swatch_size: {
		selector: '.ct-filter-widget[data-swatches-type="color"]',
		variable: 'swatch-size',
		responsive: true,
		unit: 'px',
	},

	archive_image_swatch_size: {
		selector: '.ct-card-variation-swatches [data-swatches-type="image"]',
		variable: 'swatch-size',
		responsive: true,
		unit: 'px',
	},

	single_image_swatch_size: {
		selector: '.variations_form.cart [data-swatches-type="image"]',
		variable: 'swatch-size',
		responsive: true,
		unit: 'px',
	},

	filter_widget_image_swatch_size: {
		selector: '.ct-filter-widget[data-swatches-type="image"]',
		variable: 'swatch-size',
		responsive: true,
		unit: 'px',
	},

	archive_button_swatch_size: {
		selector: '.ct-card-variation-swatches [data-swatches-type="button"]',
		variable: 'swatch-size',
		responsive: true,
		unit: 'px',
	},

	single_button_swatch_size: {
		selector: '.variations_form.cart [data-swatches-type="button"]',
		variable: 'swatch-size',
		responsive: true,
		unit: 'px',
	},

	filter_widget_button_swatch_size: {
		selector: '.ct-filter-widget[data-swatches-type="button"]',
		variable: 'swatch-size',
		responsive: true,
		unit: 'px',
	},

	archive_mixed_swatch_size: {
		selector: '.ct-card-variation-swatches [data-swatches-type="mixed"]',
		variable: 'swatch-size',
		responsive: true,
		unit: 'px',
	},

	single_mixed_swatch_size: {
		selector: '.variations_form.cart [data-swatches-type="mixed"]',
		variable: 'swatch-size',
		responsive: true,
		unit: 'px',
	},

	filter_widget_mixed_swatch_size: {
		selector: '.ct-filter-widget[data-swatches-type="mixed"]',
		variable: 'swatch-size',
		responsive: true,
		unit: 'px',
	},

	color_swatch_border_color: [
		{
			selector: '[data-swatches-type="color"] .ct-swatch',
			variable: 'swatch-border-color',
			type: 'color:default',
		},

		{
			selector: '[data-swatches-type="color"] > *:hover .ct-swatch',
			variable: 'swatch-border-color',
			type: 'color:hover',
		},

		{
			selector: '[data-swatches-type="color"] > *.active .ct-swatch',
			variable: 'swatch-border-color',
			type: 'color:active',
		},
	],

	image_swatch_border_color: [
		{
			selector: '[data-swatches-type="image"] .ct-swatch',
			variable: 'swatch-border-color',
			type: 'color:default',
		},

		{
			selector: '[data-swatches-type="image"] > *:hover .ct-swatch',
			variable: 'swatch-border-color',
			type: 'color:hover',
		},

		{
			selector: '[data-swatches-type="image"] > *.active .ct-swatch',
			variable: 'swatch-border-color',
			type: 'color:active',
		},
	],

	button_swatch_text_color: [
		{
			selector: '[data-swatches-type="button"] .ct-swatch',
			variable: 'swatch-button-text-color',
			type: 'color:default',
		},

		{
			selector: '[data-swatches-type="button"] > *:hover .ct-swatch',
			variable: 'swatch-button-text-color',
			type: 'color:hover',
		},

		{
			selector: '[data-swatches-type="button"] > *.active .ct-swatch',
			variable: 'swatch-button-text-color',
			type: 'color:active',
		},
	],

	button_swatch_border_color: [
		{
			selector: '[data-swatches-type="button"] .ct-swatch',
			variable: 'swatch-button-border-color',
			type: 'color:default',
		},

		{
			selector: '[data-swatches-type="button"] > *:hover .ct-swatch',
			variable: 'swatch-button-border-color',
			type: 'color:hover',
		},

		{
			selector: '[data-swatches-type="button"] > *.active .ct-swatch',
			variable: 'swatch-button-border-color',
			type: 'color:active',
		},
	],

	button_swatch_background_color: [
		{
			selector: '[data-swatches-type="button"] .ct-swatch',
			variable: 'swatch-button-background-color',
			type: 'color:default',
		},

		{
			selector: '[data-swatches-type="button"] > *:hover .ct-swatch',
			variable: 'swatch-button-background-color',
			type: 'color:hover',
		},

		{
			selector: '[data-swatches-type="button"] > *.active .ct-swatch',
			variable: 'swatch-button-background-color',
			type: 'color:active',
		},
	],

	mixed_swatch_border_color: [
		{
			selector: '[data-swatches-type="mixed"] .ct-swatch',
			variable: 'swatch-border-color',
			type: 'color:default',
		},

		{
			selector: '[data-swatches-type="mixed"] > *:hover .ct-swatch',
			variable: 'swatch-border-color',
			type: 'color:hover',
		},

		{
			selector: '[data-swatches-type="mixed"] > *.active .ct-swatch',
			variable: 'swatch-border-color',
			type: 'color:active',
		},
	],
})
