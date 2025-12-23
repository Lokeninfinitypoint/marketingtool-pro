import cachedFetch from 'ct-wordpress-helpers/cached-fetch'

const makeUrlFor = ({ variation, productId }) => {
	let url = new URL(ct_localizations.ajax_url)
	let params = new URLSearchParams(url.search.slice(1))

	params.append('action', 'blocksy_get_product_view_for_variation')

	if (variation.variation_id) {
		params.append('variation_id', variation.variation_id)
	}

	params.append('product_id', productId)
	params.append('retrieve_json', 'yes')

	url.search = `?${params.toString()}`

	return url.toString()
}

export const handleImagesSwap = ({ form, variation, original }) => {
	const allVariations = JSON.parse(form.dataset.product_variations)
	const currentVariation = form.closest('.product').querySelector('figure')

	let nextVariationObj = false
	let currentVariationObj = false

	if (allVariations) {
		if (variation.variation_id) {
			nextVariationObj = allVariations.find(
				({ variation_id }) =>
					parseInt(variation_id) === parseInt(variation.variation_id)
			)
		}

		if (currentVariation.dataset.currentVariation) {
			currentVariationObj = allVariations.find(
				({ variation_id }) =>
					parseInt(variation_id) ===
					parseInt(currentVariation.dataset.currentVariation)
			)
		}
	}

	let defaultCanDoInPlaceUpdate = '__DEFAULT__'

	if (
		defaultCanDoInPlaceUpdate === '__DEFAULT__' &&
		!variation.variation_id &&
		!currentVariation.dataset.currentVariation
	) {
		return
	}

	if (
		defaultCanDoInPlaceUpdate === '__DEFAULT__' &&
		parseInt(variation.variation_id) ===
			parseInt(currentVariation.dataset.currentVariation)
	) {
		return
	}

	if (!variation.variation_id) {
		currentVariation.removeAttribute('data-current-variation')
	}

	if (variation.variation_id && defaultCanDoInPlaceUpdate === '__DEFAULT__') {
		currentVariation.dataset.currentVariation = variation.variation_id
	}

	const canDoInPlaceUpdate =
		defaultCanDoInPlaceUpdate === '__DEFAULT__'
			? allVariations &&
			  [nextVariationObj, currentVariationObj].every((variation) => {
					if (!variation) {
						return true
					}

					return (
						variation.blocksy_gallery_source === 'default' ||
						!form.closest('[data-hover="swap"]')
					)
			  })
			: defaultCanDoInPlaceUpdate

	// TODO: add better check for in place update
	if (canDoInPlaceUpdate) {
		replaceImage({
			container: form,
			image: original,
		})

		return
	}

	performSwapImageViaRemoteRequest({
		form,
		variation,
		productId: form.dataset.product_id,
	})
}

const performSwapImageViaRemoteRequest = ({ form, variation, productId }) => {
	cachedFetch(makeUrlFor({ variation, productId }))
		.then((response) => response.json())
		.then(({ success, data }) => {
			if (!success) {
				return
			}

			const { images } = data

			// Doesn't have swap
			if (images.length < 2) {
				form.closest('.product')
					.querySelectorAll('.ct-swap')
					.forEach((img) => img.remove())

				replaceImage({
					container: form,
					image: images[0],
				})

				return
			}

			// Has swap

			replaceImage({
				container: form,
				image: images[0],
			})

			if (form.closest('[data-hover="swap"]')) {
				replaceImage({
					container: form,
					image: images[1],
					ctSwap: true,
				})
			}
		})
}

export const preloadImage = (imageSource) =>
	new Promise((resolve, reject) => {
		const image = new Image()
		image.onload = resolve
		image.onerror = reject
		image.src = imageSource.src
		image.sizes = imageSource.sizes
	})

export const replaceImage = ({ container, image, ctSwap = false }) => {
	if (!image) return

	const cb = () => {
		const containersToReplace = []

		const selectorsToTry = ['.ct-media-container']

		selectorsToTry.map((selector) => {
			const foundContainer = container.parentNode.querySelector(selector)

			if (foundContainer) {
				containersToReplace.push(foundContainer)
			}
		})

		containersToReplace.forEach((imgContainer) => {
			if (imgContainer.dataset.height) {
				imgContainer.dataset.height = image.full_src_h
			}

			if (imgContainer.dataset.width) {
				imgContainer.dataset.width = image.full_src_w
			}

			const itemsToReplace = ctSwap
				? 'img.ct-swap'
				: 'img:not(.ct-swap), source'

			if (ctSwap) {
				const maybeCurrentCtSwap =
					imgContainer.querySelector('.ct-swap')

				if (!maybeCurrentCtSwap) {
					const currentCtSwap = document.createElement('img')
					currentCtSwap.classList.add('ct-swap')
					imgContainer.appendChild(currentCtSwap)
				}
			}

			imgContainer.querySelectorAll(itemsToReplace).forEach((img) => {
				const closestFlexyPills = img.closest('.flexy-pills')

				img.width =
					image.width ||
					(closestFlexyPills
						? image.gallery_thumbnail_src_w
						: image.src_w)
				img.height =
					image.height ||
					(closestFlexyPills
						? image.gallery_thumbnail_src_h
						: image.src_h)
				img.src = closestFlexyPills
					? image.gallery_thumbnail_src
					: image.src

				if (image.srcset && image.srcset !== 'false') {
					img.srcset = image.srcset
				} else {
					img.removeAttribute('srcset')
				}
			})
		})
	}

	preloadImage(image).then(cb).catch(cb)
}
