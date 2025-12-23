import {
	responsiveClassesFor,
	handleBackgroundOptionFor,
	typographyOption,
	maybePromoteScalarValueIntoResponsive,
} from 'blocksy-customizer-sync'
import ctEvents from 'ct-events'

export const collectVariablesForFilters = () => ({
	...typographyOption({
		id: 'filter_panel_widgets_font',
		selector: '#woo-filters-panel .ct-widget > *:not(.widget-title)',
	}),

	filter_panel_widgets_font_color: [
		{
			selector: '#woo-filters-panel .ct-sidebar > *',
			variable: 'theme-text-color',
			type: 'color:default',
			responsive: true,
		},

		{
			selector: '#woo-filters-panel .ct-sidebar',
			variable: 'theme-link-initial-color',
			type: 'color:link_initial',
			responsive: true,
		},

		{
			selector: '#woo-filters-panel .ct-sidebar',
			variable: 'theme-link-hover-color',
			type: 'color:link_hover',
			responsive: true,
		},
	],

	filter_panel_content_vertical_alignment: {
		selector: '#woo-filters-panel',
		variable: 'vertical-alignment',
		responsive: true,
		unit: '',
	},

	// filter type - off-canvas
	filter_panel_width: {
		selector: '#woo-filters-panel[data-behaviour*="side"]',
		variable: 'side-panel-width',
		responsive: true,
		unit: '',
	},

	...handleBackgroundOptionFor({
		id: 'filter_panel_background',
		selector: '#woo-filters-panel[data-behaviour*="side"] .ct-panel-inner',
		responsive: true,
	}),

	...handleBackgroundOptionFor({
		id: 'filter_panel_backgrop',
		selector: '#woo-filters-panel[data-behaviour*="side"]',
		responsive: true,
	}),

	filter_panel_close_button_color: [
		{
			selector: '#woo-filters-panel .ct-toggle-close',
			variable: 'theme-icon-color',
			type: 'color:default',
			responsive: true,
		},

		{
			selector: '#woo-filters-panel .ct-toggle-close:hover',
			variable: 'theme-icon-color',
			type: 'color:hover',
			responsive: true,
		},
	],

	filter_panel_close_button_border_color: [
		{
			selector: '#woo-filters-panel .ct-toggle-close[data-type="type-2"]',
			variable: 'toggle-button-border-color',
			type: 'color:default',
			responsive: true,
		},

		{
			selector:
				'#woo-filters-panel .ct-toggle-close[data-type="type-2"]:hover',
			variable: 'toggle-button-border-color',
			type: 'color:hover',
			responsive: true,
		},
	],

	filter_panel_close_button_shape_color: [
		{
			selector: '#woo-filters-panel .ct-toggle-close[data-type="type-3"]',
			variable: 'toggle-button-background',
			type: 'color:default',
			responsive: true,
		},

		{
			selector:
				'#woo-filters-panel .ct-toggle-close[data-type="type-3"]:hover',
			variable: 'toggle-button-background',
			type: 'color:hover',
			responsive: true,
		},
	],

	filter_panel_close_button_border_radius: {
		selector: '#woo-filters-panel .ct-toggle-close',
		variable: 'toggle-button-radius',
		unit: 'px',
	},

	filter_panel_close_button_icon_size: {
		selector: '#woo-filters-panel .ct-toggle-close',
		variable: 'theme-icon-size',
		unit: 'px',
	},

	filter_panel_shadow: {
		selector: '#woo-filters-panel[data-behaviour*="side"]',
		forcedOutput: true,
		type: 'box-shadow',
		variable: 'theme-box-shadow',
		responsive: true,
	},

	panel_widgets_spacing: {
		selector: '#woo-filters-panel .ct-sidebar',
		variable: 'sidebar-widgets-spacing',
		responsive: true,
		unit: 'px',
	},

	// filter type - drop-down
	filter_panel_height: {
		selector: '#woo-filters-panel[data-behaviour="drop-down"]',
		variable: 'filter-panel-height',
		responsive: true,
		unit: '',
	},

	filter_panel_columns: [
		{
			selector: '#woo-filters-panel[data-behaviour="drop-down"]',
			variable: 'grid-template-columns',
			responsive: true,
			extractValue: (val) => {
				const responsive = maybePromoteScalarValueIntoResponsive(val)

				return {
					desktop: `repeat(${responsive.desktop}, 1fr)`,
					tablet: `repeat(${responsive.tablet}, 1fr)`,
					mobile: `repeat(${responsive.mobile}, 1fr)`,
				}
			},
		},
	],
})

export const mountFiltersSync = () => {
	wp.customize('woo_active_filters_label', (val) =>
		val.bind((to) => {
			document
				.querySelectorAll('.ct-active-filters > span')
				.forEach((el) => {
					el.textContent = to
				})
		})
	)

	wp.customize('filter_panel_position', (val) => {
		val.bind((to) => {
			const el = document.querySelector('#woo-filters-panel')

			if (!el) {
				return
			}

			ctEvents.trigger('ct:offcanvas:force-close', {
				container: el,
			})

			setTimeout(() => {
				el.removeAttribute('data-behaviour')
				el.classList.add('ct-no-transition')

				requestAnimationFrame(() => {
					el.dataset.behaviour = `${to}-side`

					setTimeout(() => {
						el.classList.remove('ct-no-transition')
					})
				})
			}, 300)
		})
	})

	wp.customize('woocommerce_filter_visibility', (val) => {
		val.bind((to) => {
			const trigger = document.querySelector('.ct-toggle-filter-panel')

			if (trigger) {
				responsiveClassesFor('woocommerce_filter_visibility', trigger)
			}
		})
	})

	wp.customize('filter_panel_visibility', (val) => {
		val.bind((to) => {
			const trigger = document.querySelector('#woo-filters-panel')

			if (trigger) {
				responsiveClassesFor('filter_panel_visibility', trigger)
			}
		})
	})

	wp.customize('woocommerce_filter_label', (val) => {
		val.bind((to) => {
			const trigger = document.querySelector('.ct-toggle-filter-panel')

			if (trigger) {
				const maybeIcon = trigger.querySelector('.ct-icon-container')

				trigger.innerHTML = `${maybeIcon?.outerHTML || ''}${to}`
			}
		})
	})

	wp.customize('filter_panel_close_button_type', (val) => {
		val.bind((to) => {
			let offcanvasModalClose = document.querySelector(
				'#woo-filters-panel .ct-toggle-close'
			)

			if (!offcanvasModalClose) {
				return
			}

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
