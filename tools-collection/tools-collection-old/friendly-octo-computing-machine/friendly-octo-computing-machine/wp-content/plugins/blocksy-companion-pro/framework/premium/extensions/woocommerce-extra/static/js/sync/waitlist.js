import {
	handleBackgroundOptionFor,
	typographyOption,
} from 'blocksy-customizer-sync'

export const collectVariablesForWaitlist = () => ({
	waitlist_container_max_width: {
		selector: '.ct-product-waitlist',
		variable: 'container-max-width',
		responsive: true,
		unit: '%',
	},

	...typographyOption({
		id: 'waitlist_title_font',
		selector: '.ct-waitlist-title',
	}),

	waitlist_title_color: {
		selector: '.ct-waitlist-title',
		variable: 'theme-heading-color',
		type: 'color',
	},

	...typographyOption({
		id: 'waitlist_message_font',
		selector: '.ct-waitlist-message',
	}),

	waitlist_message_color: {
		selector: '.ct-product-waitlist p',
		variable: 'theme-text-color',
		type: 'color',
	},

	waitlist_form_border: {
		selector: '.ct-product-waitlist',
		variable: 'container-border',
		type: 'border',
		responsive: true,
		skip_none: true,
	},

	...handleBackgroundOptionFor({
		id: 'waitlist_form_background',
		selector: '.ct-product-waitlist',
		responsive: true,
	}),

	waitlist_form_padding: {
		selector: '.ct-product-waitlist',
		type: 'spacing',
		variable: 'container-padding',
		responsive: true,
	},

	waitlist_form_border_radius: {
		selector: '.ct-product-waitlist',
		type: 'spacing',
		variable: 'container-border-radius',
		responsive: true,
	},
})

export const mountSizeWaitlistSync = () => {
	wp.customize('waitlist_type', (val) => {
		val.bind((to) => {
			const waitlistEls = document.querySelectorAll(
				'.ct-product-waitlist'
			)

			waitlistEls.forEach((waitlistEl) => {
				waitlistEl.dataset.type = to
			})
		})
	})
}
