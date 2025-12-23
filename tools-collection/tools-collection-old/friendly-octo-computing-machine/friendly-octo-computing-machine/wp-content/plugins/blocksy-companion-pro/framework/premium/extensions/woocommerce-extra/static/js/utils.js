export const getProductIdFromElement = (el) => {
	if (el.dataset.product_id) {
		return parseInt(el.dataset.product_id)
	}

	const maybeProduct = el.closest('.product')

	if (!maybeProduct) {
		return null
	}

	const productId = Array.from(maybeProduct.classList)
		.find((className) => className.indexOf('post-') === 0)
		.split('-')[1]

	return parseInt(productId)
}
