import { getProductIdFromElement } from '../utils'

const syncLikesClasses = (likes) => {
	let selector = [
		'[class*="ct-wishlist-button"]',
		'.ct-wishlist-remove',
		'.wishlist-product-remove > .remove',
		'.product-mobile-actions > [href*="wishlist-remove"]',
	].join(', ')

	;[...document.querySelectorAll(selector)].map((el) => {
		el.dataset.buttonState = ''

		const isVariable = typeof el.dataset.variable !== 'undefined'

		if (!isVariable) {
			const productId = getProductIdFromElement(el)

			if (likes.items.some((item) => item.id === productId)) {
				el.dataset.buttonState = 'active'
			}

			return
		}

		const productCard = el.closest('.product')

		if (
			productCard.querySelector('.add_to_cart_button') &&
			productCard
				.querySelector('.add_to_cart_button')
				.getAttribute('href')
				.includes('variation_id')
		) {
			const params = new URLSearchParams(
				productCard
					.querySelector('.add_to_cart_button')
					.getAttribute('href')
			)
			const variation_id = parseFloat(params.get('variation_id'))

			const additional_attrs = {}

			productCard
				.querySelectorAll('select[data-attribute_name]')
				.forEach((select) => {
					const key = select.dataset.attribute_name.replace(
						'attribute_',
						''
					)

					additional_attrs[key] = select.value
				})

			const item = { id: variation_id }

			if (Object.keys(additional_attrs).length) {
				item.attributes = additional_attrs
			}

			if (
				likes.items.some((item) => {
					return (
						item.id === variation_id &&
						Object.keys(item?.attributes || {}).every((aKey) => {
							return (
								item?.attributes?.[aKey] &&
								additional_attrs[aKey] &&
								item.attributes[aKey] === additional_attrs[aKey]
							)
						})
					)
				})
			) {
				el.dataset.buttonState = 'active'
			}
		}
	})
}

ctEvents.on('blocksy:wishlist:sync', () =>
	syncLikesClasses(window.ct_localizations.blc_ext_wish_list.list)
)

export default syncLikesClasses
