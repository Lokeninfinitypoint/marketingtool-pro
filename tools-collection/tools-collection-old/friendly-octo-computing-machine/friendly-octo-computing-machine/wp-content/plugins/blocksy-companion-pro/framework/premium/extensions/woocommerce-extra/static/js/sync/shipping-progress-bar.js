export const collectVariablesForShippingProgressBar = () => ({

	shipping_progress_bar_color: [
		{
			selector: '[class*="ct-shipping-progress"]',
			variable: 'product-progress-bar-initial-color',
			type: 'color:default',
			// responsive: true,
		},

		{
			selector: '[class*="ct-shipping-progress"]',
			variable: 'product-progress-bar-active-color',
			type: 'color:active',
			// responsive: true,
		},

		{
			selector: '[class*="ct-shipping-progress"]',
			variable: 'product-progress-bar-active-color-2',
			type: 'color:active_2',
			// responsive: true,
		},
	],

})