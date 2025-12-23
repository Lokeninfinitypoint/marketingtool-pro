import { getProductIdFromElement } from '../utils'
import { prepareListWithSimpleProduct } from './common'

export const maybeHandleCompareArchiveProduct = (el, operation) => {
	let newList = []

	newList = prepareListWithSimpleProduct(
		getProductIdFromElement(el),
		operation
	)

	return newList
}
