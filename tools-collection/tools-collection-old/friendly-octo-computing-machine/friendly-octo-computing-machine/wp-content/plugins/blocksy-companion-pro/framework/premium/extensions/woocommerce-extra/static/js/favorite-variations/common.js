const onFoundVariation = ({ container, favoritesButton, eventData }) => {
	const { variation_id, attributes } = eventData

	const wishlist = ct_localizations.blc_ext_wish_list.list.items
	const additional_attrs = {
		...attributes,
	}

	Object.keys(attributes).forEach((key) => {
		if (attributes[key] === '') {
			additional_attrs[key.trim()] = container
				.querySelector(`select[name="${key}"]`)
				.value.trim()
		}
	})

	const item = wishlist.find(
		(w) =>
			w.id === variation_id &&
			Object.keys(w?.attributes || {}).every(
				(aKey) =>
					additional_attrs?.[`attribute_${aKey}`] &&
					w.attributes[`${aKey}`] &&
					w.attributes[`${aKey}`] ===
						additional_attrs[`attribute_${aKey}`]
			)
	)

	if (item) {
		favoritesButton.dataset.buttonState = 'active'
	} else {
		favoritesButton.dataset.buttonState = ''
	}
}

const onResetData = ({ el, favoritesButton, productId }) => {
	const hasAttributes = hasAttributeSelected(el)

	setTimeout(() => {
		if (hasAttributes) {
			favoritesButton.dataset.buttonState = 'disabled'
		} else {
			favoritesButton.dataset.id = productId
			favoritesButton.dataset.buttonState = ''

			const wishlist = ct_localizations.blc_ext_wish_list.list.items

			const item = wishlist.find(
				(i) => i.id === productId && !i.attributes
			)

			if (item) {
				favoritesButton.dataset.buttonState = 'active'
			}
		}
	})
}

export const computeFavorite = ({
	el,
	form,
	container,
	favoritesButton,
	productId,
	type,
	eventData,
}) => {
	if (type === 'found_variation') {
		onFoundVariation({ container, favoritesButton, eventData })
	}

	if (type === 'reset_data') {
		onResetData({ el, favoritesButton, productId })
	}
}

export const hasAttributeSelected = (el) =>
	[...el.querySelectorAll('select')].some((swatchesSelect) => {
		if (!swatchesSelect) {
			return false
		}

		return !!swatchesSelect.value
	})
