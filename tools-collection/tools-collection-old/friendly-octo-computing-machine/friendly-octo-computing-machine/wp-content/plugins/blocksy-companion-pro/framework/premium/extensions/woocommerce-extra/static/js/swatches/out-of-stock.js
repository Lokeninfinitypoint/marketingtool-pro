import cachedFetch from 'ct-wordpress-helpers/cached-fetch'

const cleanupId = (id) => {
	if (id.includes('--')) {
		return id.split('--')[0]
	}

	return id
}

// product_id => {
//    Strategy #1:
//
//    attributes_stock: {
//      attribute_name: {
//        valid: [],
//        invalid: []
//      }
//    }
//    out_of_stock_variations: [],
//
//    ==========
//
//    Strategy #2:
//
//    all_variations: []
// }
const variations = {}

const getRelevantVariations = async (form, args = {}) => {
	args = {
		selectedAttributes: {},
		form: null,
		attribute_name: '',

		...args,
	}

	// More often than not, the product ID will be set on the form itself.
	let productId = parseFloat(form.dataset.product_id)

	if (!productId) {
		const maybeClosestPost = form.closest('[class*="post-"]')

		if (maybeClosestPost) {
			const matchingClass = [...maybeClosestPost.classList].find((c) =>
				c.match(/^post-/)
			)

			if (matchingClass) {
				productId = parseFloat(matchingClass.split('-')[1])
			}
		}
	}

	// Can't compute product ID for some reason, returning empty array.
	if (!productId) {
		return []
	}

	let cacheKey = productId

	// Woo Bundled Products (official extension)
	if (form.dataset.bundled_item_id) {
		cacheKey = form.dataset.bundled_item_id
	}

	if (variations[cacheKey]) {
		return variations[cacheKey]
	}

	// Product has a lot of variations, starting to load them via AJAX
	if (form.dataset.product_variations === 'false') {
		const response = await cachedFetch(
			ct_localizations.ajax_url +
				'?action=blocksy_swatches_get_product_out_of_stock_variations',
			{
				product_id: productId,
			}
		)

		const result = await response.json()

		variations[cacheKey] = {
			fast_variations_data: result.data.variations_data,
		}

		return variations[cacheKey]
	}

	// All variations are available. Just populating the cache to skip
	// further JSON parsing calls.
	const allVariations = JSON.parse(form.dataset.product_variations)

	variations[cacheKey] = {
		all_variations: allVariations,
	}

	return variations[cacheKey]
}

const markAsOutOfStock = (swatch) => {
	const hideOutOfStock =
		ct_localizations.swatches_data.woocommerce_hide_out_of_stock_items

	if (hideOutOfStock) {
		markAsHidden(swatch)
		return
	}

	if (swatch.classList.contains('ct-out-of-stock')) {
		return
	}

	let outOfStockLabel = ''

	if (
		wc_add_to_cart_variation_params &&
		wc_add_to_cart_variation_params.i18n_out_of_stock
	) {
		outOfStockLabel = wc_add_to_cart_variation_params.i18n_out_of_stock
	}

	swatch.classList.remove('ct-hidden')
	swatch.classList.add('ct-out-of-stock')

	const maybeTooltip = swatch.querySelector('.ct-tooltip')

	if (
		maybeTooltip &&
		!maybeTooltip.textContent.includes(outOfStockLabel) &&
		maybeTooltip.dataset.tooltipType !== 'image'
	) {
		maybeTooltip.textContent = `${maybeTooltip.textContent} - ${outOfStockLabel}`
	}
}

const markAsHidden = (swatch) => {
	if (swatch.classList.contains('ct-hidden')) {
		return
	}

	markAsInStock(swatch)
	swatch.classList.add('ct-hidden')
}

const markAsInStock = (swatch) => {
	if (
		!swatch.classList.contains('ct-out-of-stock') &&
		!swatch.classList.contains('ct-hidden')
	) {
		return
	}

	let outOfStockLabel = ''

	if (
		wc_add_to_cart_variation_params &&
		wc_add_to_cart_variation_params.i18n_out_of_stock
	) {
		outOfStockLabel = wc_add_to_cart_variation_params.i18n_out_of_stock
	}

	swatch.classList.remove('ct-out-of-stock')
	swatch.classList.remove('ct-hidden')

	const maybeTooltip = swatch.querySelector('.ct-tooltip')

	if (
		maybeTooltip &&
		!maybeTooltip.querySelector('.ct-media-container') &&
		maybeTooltip.dataset.tooltipType !== 'image'
	) {
		maybeTooltip.textContent = maybeTooltip.textContent.replace(
			` - ${outOfStockLabel}`,
			''
		)
	}
}

const applyOutOfStockForSwatch = async (swatch, args = {}) => {
	args = {
		selectedAttributes: {},
		form: null,
		attribute_name: '',

		...args,
	}

	let isOutOfStock = false
	let isHidden = false

	const variationsDescriptor = await getRelevantVariations(args.form, {
		attribute_name: args.attribute_name,

		// By not using transformedSelectedAttributes to make the request,
		// we will fill the cache much faster with all the variations
		// and we will avoid lots of small requests.
		selectedAttributes: args.selectedAttributes,
	})

	const transformedSelectedAttributes = {
		...args.selectedAttributes,
		[args.attribute_name]: swatch.dataset.value,
	}

	// Strategy #1
	// Usually, this is the case when the product has a lot of variations.
	if (variationsDescriptor.fast_variations_data) {
		const allVariations = Object.values(
			variationsDescriptor.fast_variations_data
		)

		const allMatching = allVariations.filter((variation) => {
			return (
				variation.attributes[args.attribute_name] ===
					swatch.dataset.value ||
				variation.attributes[args.attribute_name] === ''
			)
		})

		const alwaysOutOfStock = allMatching.every(
			(variation) => !variation.is_in_stock
		)

		const maybeFoundVariation = allVariations.find((variation) => {
			if (!variation.attributes) {
				return false
			}

			return Object.keys(variation.attributes).every((key) => {
				return (
					variation.attributes[key] ===
						transformedSelectedAttributes[key] ||
					variation.attributes[key] === ''
				)
			})
		})

		if (maybeFoundVariation && !maybeFoundVariation.is_in_stock) {
			isOutOfStock = true
		}

		if (alwaysOutOfStock) {
			isOutOfStock = true
		}

		if (
			allVariations.length > 0 &&
			Object.keys(transformedSelectedAttributes).length ===
				Object.keys(allVariations[0].attributes).length
		) {
			if (
				!allVariations.find((variation) => {
					return Object.keys(variation.attributes).every((key) => {
						return (
							variation.attributes[key] ===
								transformedSelectedAttributes[key] ||
							variation.attributes[key] === ''
						)
					})
				})
			) {
				isHidden = true
			}
		}
	}

	// Strategy #2
	//
	// If all variations are available, check is simpler.
	if (variationsDescriptor.all_variations) {
		const allVariations = variationsDescriptor.all_variations

		const allMatching = allVariations.filter((variation) => {
			return (
				variation.attributes[args.attribute_name] ===
					swatch.dataset.value ||
				variation.attributes[args.attribute_name] === ''
			)
		})

		const alwaysOutOfStock = allMatching.every(
			(variation) => !variation.is_in_stock
		)

		const maybeFoundVariation = allVariations.find((variation) => {
			if (!variation.attributes) {
				return false
			}

			return Object.keys(variation.attributes).every((key) => {
				return (
					variation.attributes[key] ===
						transformedSelectedAttributes[key] ||
					variation.attributes[key] === ''
				)
			})
		})

		if (maybeFoundVariation && !maybeFoundVariation.is_in_stock) {
			isOutOfStock = true
		}

		if (alwaysOutOfStock) {
			isOutOfStock = true
		}

		if (
			allVariations.length > 0 &&
			Object.keys(transformedSelectedAttributes).length ===
				Object.keys(allVariations[0].attributes).length
		) {
			if (
				!allVariations.find((variation) => {
					return Object.keys(variation.attributes).every((key) => {
						return (
							variation.attributes[key] ===
								transformedSelectedAttributes[key] ||
							variation.attributes[key] === ''
						)
					})
				})
			) {
				isHidden = true
			}
		}
	}

	if (isHidden) {
		markAsHidden(swatch)
		return
	}

	if (isOutOfStock) {
		markAsOutOfStock(swatch)
		return
	}

	markAsInStock(swatch)
}

export const handleOutofStock = (el) => {
	const form = el.closest('[data-product_variations]')

	if (!form) {
		return
	}

	const selectsWithAttributes = Array.from(
		form.querySelectorAll('select')
	).filter((s) => s.closest('.ct-variation-swatches'))

	const selectedAttributes = selectsWithAttributes
		.filter((s) => s.value)
		.reduce((acc, s) => {
			acc[cleanupId(s.dataset.attribute_name)] = s.value

			return acc
		}, {})

	selectsWithAttributes.forEach((select) => {
		;[
			...select
				.closest('.ct-variation-swatches')
				.querySelectorAll('[data-value]'),
		].map((swatch) =>
			applyOutOfStockForSwatch(swatch, {
				form,
				selectedAttributes,
				attribute_name: cleanupId(select.dataset.attribute_name),
			})
		)
	})
}
