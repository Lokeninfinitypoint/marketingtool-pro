import { getProductIdFromElement } from '../utils'
import {
	prepareListWithSimpleProduct,
	prepareListWithVariableItem,
} from './common'

export const maybeHandleFavoriteArchiveProduct = (el, operation, variable) => {
	const productCard = el.closest('.product')
	let newList = []

	if (
		productCard.querySelector('.add_to_cart_button') &&
		productCard
			.querySelector('.add_to_cart_button')
			.getAttribute('href')
			.includes('variation_id') &&
		variable
	) {
		const params = new URLSearchParams(
			productCard
				.querySelector('.add_to_cart_button')
				.getAttribute('href')
		)
		const variation_id = parseFloat(params.get('variation_id'))

		newList = prepareListWithVariableItem(
			productCard,
			variation_id,
			operation
		)
	} else {
		newList = prepareListWithSimpleProduct(
			getProductIdFromElement(el),
			operation
		)
	}

	return newList
}
