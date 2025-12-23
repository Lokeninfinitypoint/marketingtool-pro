import {
	responsiveClassesFor,
	handleBackgroundOptionFor,
} from 'blocksy-customizer-sync'

export const mountFloatingBarSync = () => {
	wp.customize('floatingBarImageVisibility', (val) =>
		val.bind((to) => {
			responsiveClassesFor(
				'floatingBarImageVisibility',
				document.querySelector('.ct-floating-bar .ct-media-container')
			)
		})
	)

	wp.customize('floatingBarTitleVisibility', (val) =>
		val.bind((to) => {
			responsiveClassesFor(
				'floatingBarTitleVisibility',
				document.querySelector('.ct-floating-bar .product-title')
			)
		})
	)

	wp.customize('floatingBarPriceStockVisibility', (val) =>
		val.bind((to) => {
			responsiveClassesFor(
				'floatingBarPriceStockVisibility',
				document.querySelector('.ct-floating-bar .product-price')
			)
		})
	)

	wp.customize('floatingBarVisibility', (val) =>
		val.bind((to) => {
			responsiveClassesFor(
				'floatingBarVisibility',
				document.querySelector('.ct-floating-bar')
			)
		})
	)
}

export const collectVariablesForFloatingBar = () => ({
	floatingBarFontColor: {
		selector: '.ct-floating-bar .product-title, .ct-floating-bar .price',
		variable: 'theme-text-color',
		type: 'color',
		responsive: true,
	},

	floatingBarBackground: {
		selector: '.ct-floating-bar',
		variable: 'backgroundColor',
		type: 'color',
	},

	...handleBackgroundOptionFor({
		id: 'floatingBarBackground',
		selector: '.ct-floating-bar',
		responsive: true,
	}),

	floatingBarShadow: {
		selector: '.ct-floating-bar',
		type: 'box-shadow',
		variable: 'theme-box-shadow',
		responsive: true,
	},

	floating_bar_position: [
		{
			selector: '.ct-floating-bar',
			variable: 'top-position-override',
			extractValue: (value) => {
				value = value.desktop
					? value
					: {
							desktop: value,
							tablet: value,
							mobile: value,
					  }

				return {
					desktop:
						value.desktop === 'top'
							? 'var(--top-position)'
							: 'var(--false)',

					tablet:
						value.tablet === 'top'
							? 'var(--top-position)'
							: 'var(--false)',

					mobile:
						value.mobile === 'top'
							? 'var(--top-position)'
							: 'var(--false)',
				}
			},
			responsive: true,
		},

		{
			selector: '.ct-floating-bar',
			variable: 'translate-offset',
			extractValue: (value) => {
				value = value.desktop
					? value
					: {
							desktop: value,
							tablet: value,
							mobile: value,
					  }

				return {
					desktop: value.desktop === 'top' ? '-75px' : '75px',
					tablet: value.tablet === 'top' ? '-75px' : '75px',
					mobile: value.mobile === 'top' ? '-75px' : '75px',
				}
			},
			responsive: true,
		},
	],
})
