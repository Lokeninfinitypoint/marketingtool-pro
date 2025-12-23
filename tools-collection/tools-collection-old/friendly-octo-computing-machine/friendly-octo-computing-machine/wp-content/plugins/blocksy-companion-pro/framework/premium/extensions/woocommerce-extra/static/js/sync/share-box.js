export const collectVariablesForShareBox = () => ({

	product_share_items_icon_color: [
		{
			selector: '[data-prefix="product"] .ct-share-box',
			variable: 'theme-icon-color',
			type: 'color:default',
		},

		{
			selector: '[data-prefix="product"] .ct-share-box',
			variable: 'theme-icon-hover-color',
			type: 'color:hover',
		},
	],

})
