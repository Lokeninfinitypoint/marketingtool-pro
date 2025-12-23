import { getProductIdFromElement } from '../utils'

export const maybeHandleCompareTableProduct = (el) => {
	const item_id = getProductIdFromElement(el)
	const item = { id: parseInt(item_id) }

	const compareView = Object.values(
		ct_localizations.blc_ext_compare_list.list
	)

	return compareView.filter((w) => w.id !== item.id)
}
