export const prepareListWithVariableItem = (
	parent,
	variation_id,
	operation
) => {
	if (!parent) {
		return [...ct_localizations.blc_ext_wish_list.list.items]
	}

	const additional_attrs = {}

	parent.querySelectorAll('select[data-attribute_name]').forEach((select) => {
		const key = select.dataset.attribute_name.replace('attribute_', '')

		additional_attrs[key] = select.value
	})

	const item = { id: variation_id }

	if (operation === 'add') {
		if (Object.keys(additional_attrs).length) {
			item.attributes = additional_attrs
		}

		return [...ct_localizations.blc_ext_wish_list.list.items, item]
	}

	if (operation === 'remove') {
		const wishlist = ct_localizations.blc_ext_wish_list.list.items

		return wishlist.filter(
			(w) =>
				w.id !== item.id ||
				Object.keys(w?.attributes || {}).some((aKey) => {
					return (
						item?.attributes?.[aKey] &&
						w.attributes[aKey] &&
						w.attributes[aKey] !== item?.attributes[aKey]
					)
				})
		)
	}
}

export const prepareListWithSimpleProduct = (productId, operation) => {
	const item = { id: parseFloat(productId) }

	if (operation === 'add') {
		return [...ct_localizations.blc_ext_wish_list.list.items, item]
	}

	if (operation === 'remove') {
		const wishlist = ct_localizations.blc_ext_wish_list.list.items

		return wishlist.filter((w) => w.id !== item.id)
	}
}
