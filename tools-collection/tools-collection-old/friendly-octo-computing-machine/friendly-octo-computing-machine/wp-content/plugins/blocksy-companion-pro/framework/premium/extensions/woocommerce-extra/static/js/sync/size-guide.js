import { handleBackgroundOptionFor } from 'blocksy-customizer-sync'

export const mountSizeGuideSync = () => {
	wp.customize('size_guide_close_button_type', (val) => {
		val.bind((to) => {
			let offcanvasModalClose = document.querySelector(
				'#ct-size-guide-modal .ct-toggle-close'
			)

			setTimeout(() => {
				offcanvasModalClose.classList.add('ct-disable-transitions')

				requestAnimationFrame(() => {
					if (offcanvasModalClose) {
						offcanvasModalClose.dataset.type = to
					}

					setTimeout(() => {
						offcanvasModalClose.classList.remove(
							'ct-disable-transitions'
						)
					})
				})
			}, 300)
		})
	})
}

export const collectVariablesForSizeGuide = () => ({
	size_guide_side_panel_width: {
		selector: '#ct-size-guide-modal',
		variable: 'side-panel-width',
		responsive: true,
		unit: '',
	},

	size_guide_modal_radius: {
		selector: '#ct-size-guide-modal .ct-container',
		type: 'spacing',
		variable: 'theme-border-radius',
		responsive: true,
	},

	size_guide_modal_background: [
		...handleBackgroundOptionFor({
			id: 'size_guide_modal_background',
			selector: '#ct-size-guide-modal .ct-container',
			responsive: true,
		}).size_guide_modal_background,

		...handleBackgroundOptionFor({
			id: 'size_guide_modal_background',
			selector: '#ct-size-guide-modal .ct-panel-inner',
			responsive: true,
		}).size_guide_modal_background,
	],

	...handleBackgroundOptionFor({
		id: 'size_guide_modal_backdrop',
		selector: '#ct-size-guide-modal',
		responsive: true,
	}),

	size_guide_modal_shadow: {
		selector: '#ct-size-guide-modal .ct-container',
		type: 'box-shadow',
		variable: 'theme-box-shadow',
		responsive: true,
	},

	size_guide_panel_shadow: {
		selector: '#ct-size-guide-modal .ct-panel-inner',
		type: 'box-shadow',
		variable: 'theme-box-shadow',
		responsive: true,
	},

	size_guide_close_button_icon_size: {
		selector: '#ct-size-guide-modal .ct-toggle-close',
		variable: 'theme-icon-size',
		unit: 'px',
	},

	size_guide_close_button_color: [
		{
			selector: '#ct-size-guide-modal .ct-toggle-close',
			variable: 'theme-icon-color',
			type: 'color:default',
			responsive: true,
		},

		{
			selector: '#ct-size-guide-modal .ct-toggle-close:hover',
			variable: 'theme-icon-color',
			type: 'color:hover',
			responsive: true,
		},
	],

	size_guide_close_button_border_color: [
		{
			selector:
				'#ct-size-guide-modal .ct-toggle-close[data-type="type-2"]',
			variable: 'toggle-button-border-color',
			type: 'color:default',
			responsive: true,
		},

		{
			selector:
				'#ct-size-guide-modal .ct-toggle-close[data-type="type-2"]:hover',
			variable: 'toggle-button-border-color',
			type: 'color:hover',
			responsive: true,
		},
	],

	size_guide_close_button_shape_color: [
		{
			selector:
				'#ct-size-guide-modal .ct-toggle-close[data-type="type-3"]',
			variable: 'toggle-button-background',
			type: 'color:default',
			responsive: true,
		},

		{
			selector:
				'#ct-size-guide-modal .ct-toggle-close[data-type="type-3"]:hover',
			variable: 'toggle-button-background',
			type: 'color:hover',
			responsive: true,
		},
	],

	size_guide_close_button_border_radius: {
		selector: '#ct-size-guide-modal .ct-toggle-close',
		variable: 'toggle-button-radius',
		unit: 'px',
	},
})
