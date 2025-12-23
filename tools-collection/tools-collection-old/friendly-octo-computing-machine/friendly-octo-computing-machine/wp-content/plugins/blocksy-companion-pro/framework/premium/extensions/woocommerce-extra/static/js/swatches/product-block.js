import $ from 'jquery'

import { computeSwatch } from './common'

export const maybeHandleSingleProductBlockSwatches = (el) => {
	if (!el.closest('.wp-block-woocommerce-single-product')) {
		return
	}

	const forms = el
		.closest('.wp-block-woocommerce-single-product')
		.querySelectorAll('.variations_form.cart')

	if (!forms || !forms.length) {
		return
	}

	// TODO: refactor this to use the new event system
	forms.forEach((form) => {
		if (form.hasEventListener) {
			return
		}

		form.hasEventListener = true

		computeSwatch(form)

		$(form).on('found_variation', () => computeSwatch(form))
		$(form).on('reset_data', () => computeSwatch(form))
	})
}
