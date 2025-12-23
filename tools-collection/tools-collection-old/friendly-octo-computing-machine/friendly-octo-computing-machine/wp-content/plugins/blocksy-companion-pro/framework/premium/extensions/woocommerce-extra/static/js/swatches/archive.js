import $ from 'jquery'

import { computeSwatch } from './common'
import { handleImagesSwap } from './images-update'

$(document.body).on('should_send_ajax_request.adding_to_cart', (e, button) => {
	const btn = button[0]

	if (
		btn.dataset.blocksy_archive_add_to_cart &&
		btn.hasAttribute('disabled')
	) {
		return false
	}
})

export const maybeHandleArchiveSwatches = (el) => {
	if (
		!el.closest('.product') ||
		!el.closest('.product').closest('.products')
	) {
		return
	}

	const form = el
		.closest('.product')
		.querySelector('[data-product_variations]')

	if (!form || form.hasEventListener) {
		return
	}

	form.hasEventListener = true

	const getDynamicData = () => {
		return JSON.parse(form.dataset.dynamicCardData)
	}

	let firstVariation = null

	$(form).on('found_variation', function (event, variation) {
		const dynamicData = getDynamicData()

		if (!firstVariation) {
			firstVariation = variation
		}

		computeSwatch(form, {
			computeArchiveUrl: !dynamicData.isCompleteVariationsForm,
		})

		if (!dynamicData.isCompleteVariationsForm) {
			return
		}

		const currentPrice = form.closest('.product').querySelector('.price')

		if (currentPrice && variation.price_html) {
			currentPrice.insertAdjacentHTML('afterend', variation.price_html)

			currentPrice.remove()
		}

		handleImagesSwap({
			form,
			variation,
			original: variation.image?.src
				? variation.image
				: variation.blocksy_original_image,
		})

		const maybeButton = form.closest('.product').querySelector('.button')

		if (maybeButton) {
			if (maybeButton.querySelector('.ct-icon')) {
				const tooltip = maybeButton.querySelector('.ct-tooltip')

				if (tooltip) {
					tooltip.innerHTML = dynamicData.simple.text
				}
			} else {
				maybeButton.innerHTML = dynamicData.simple.text
			}

			const link = dynamicData.simple.link

			const url = new URL(dynamicData.simple.link, window.location)
			const searchParams = new URLSearchParams(url.search)

			Object.keys(variation.attributes).map((key) => {
				let value = variation.attributes[key]

				if (value === '') {
					value = form.querySelector(`[name="${key}"]`).value.trim()
					$(maybeButton).data(key, value)
				}

				searchParams.set(key, value)
			})

			searchParams.set('variation_id', variation.variation_id)

			url.search = searchParams.toString()

			maybeButton.href = url.toString()

			maybeButton.dataset.product_sku = variation.sku
			maybeButton.dataset.product_id = variation.variation_id

			maybeButton.dataset.blocksy_archive_add_to_cart = 'yes'

			maybeButton.classList.add('add_to_cart_button', 'ajax_add_to_cart')

			maybeButton.classList.remove('added')

			if (form.closest('.product').querySelector('.added_to_cart')) {
				form.closest('.product')
					.querySelector('.added_to_cart')
					.remove()
			}

			maybeButton.classList.remove('disabled')
			maybeButton.removeAttribute('disabled')

			if (!variation.is_in_stock) {
				maybeButton.classList.add('disabled')
				maybeButton.setAttribute('disabled', 'disabled')
			}
		}
	})

	$(form).on('reset_data', function (event, variation) {
		const dynamicData = getDynamicData()

		computeSwatch(form, {
			computeArchiveUrl: !dynamicData.isCompleteVariationsForm,
		})

		if (!dynamicData.isCompleteVariationsForm) {
			return
		}

		const vars =
			JSON.parse(form.dataset.product_variations)[0] || firstVariation

		if (vars && vars.blocksy_original_image) {
			handleImagesSwap({
				form,
				variation: {},
				original: vars.blocksy_original_image,
			})
		}

		const maybeButton = form.closest('.product').querySelector('.button')

		const currentPrice = form.closest('.product').querySelector('.price')

		if (currentPrice && dynamicData.variable.price) {
			currentPrice.insertAdjacentHTML(
				'afterend',
				dynamicData.variable.price
			)
			currentPrice.remove()
		}

		if (maybeButton) {
			maybeButton.classList.remove('disabled')
			maybeButton.removeAttribute('disabled')

			if (maybeButton.querySelector('.ct-icon')) {
				const tooltip = maybeButton.querySelector('.ct-tooltip')

				if (tooltip) {
					tooltip.innerHTML = dynamicData.variable.text
				}
			} else {
				maybeButton.innerHTML = dynamicData.variable.text
			}
			maybeButton.href = dynamicData.variable.link

			maybeButton.classList.remove(
				'add_to_cart_button',
				'ajax_add_to_cart'
			)
		}
	})

	$(form).wc_variation_form()
}
