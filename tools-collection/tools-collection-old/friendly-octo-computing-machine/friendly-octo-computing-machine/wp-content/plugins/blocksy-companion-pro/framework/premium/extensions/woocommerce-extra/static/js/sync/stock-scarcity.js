export const collectVariablesForWStockScarcity = () => ({
	stock_scarcity_bar_height: {
		selector: '.ct-product-stock-scarcity',
		variable: 'product-progress-bar-height',
		responsive: true,
		unit: 'px',
	},

	stock_scarcity_bar_color: [
		{
			selector: '.ct-product-stock-scarcity',
			variable: 'product-progress-bar-initial-color',
			type: 'color:default',
			// responsive: true,
		},

		{
			selector: '.ct-product-stock-scarcity',
			variable: 'product-progress-bar-active-color',
			type: 'color:active',
			// responsive: true,
		},

		{
			selector: '.ct-product-stock-scarcity',
			variable: 'product-progress-bar-active-color-2',
			type: 'color:active_2',
			// responsive: true,
		},
	],
})

export const mountStockScarcitySync = () => {
	wp.customize('product_stock_scarcity_title', (val) => {
		val.bind((to) => {
			const scarcityEls = document.querySelectorAll(
				'.ct-product-stock-scarcity'
			)

			scarcityEls.forEach((scarcityEl) => {
				const maxQty = scarcityEl.dataset.items

				scarcityEl.querySelector('.ct-message').innerHTML = to.replace(
					'{items}',
					maxQty
				)
			})
		})
	})

	wp.customize('product_stock_scarcity_min', (val) => {
		val.bind((to) => {
			const scarcityEls = document.querySelectorAll(
				'.ct-product-stock-scarcity'
			)

			scarcityEls.forEach((scarcityEl) => {
				const total = scarcityEl.dataset.items
				let persent = (total / to || 1) * 100

				if (persent >= 100) {
					scarcityEl.hidden = true
					persent = 100
				} else {
					scarcityEl.removeAttribute('hidden')
				}

				scarcityEl.querySelector(
					'.ct-progress-bar span'
				).style.width = `${persent}%`
			})
		})
	})
}
