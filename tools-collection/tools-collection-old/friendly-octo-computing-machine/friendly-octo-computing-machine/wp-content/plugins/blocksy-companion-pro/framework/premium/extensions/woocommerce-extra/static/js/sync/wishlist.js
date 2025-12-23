export const collectVariablesForWishlistShareBox = () => ({
	wish_list_share_box_icon_size: {
		selector: '.ct-woo-account .ct-share-box',
		variable: 'theme-icon-size',
		responsive: true,
		unit: '',
	},

	wish_list_share_box_icons_spacing: {
		selector: '.ct-woo-account .ct-share-box',
		variable: 'items-spacing',
		responsive: true,
		unit: '',
	},

	wish_list_share_items_icon_color: [
		{
			selector: '.ct-woo-account .ct-share-box',
			variable: 'theme-icon-color',
			type: 'color:default',
		},

		{
			selector: '.ct-woo-account .ct-share-box',
			variable: 'theme-icon-hover-color',
			type: 'color:hover',
		},
	],
})
