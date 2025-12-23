import { registerDynamicChunk } from 'blocksy-frontend'

const COOKIE_NAME = 'blc_products_waitlist'
const COOKIE_LIFETIME = 365 * 24 * 60 * 60 * 1000

const changeCounter = (el, value, messsage) => {
	const maybeCounters = el.querySelectorAll('.ct-waitlist-users')

	if (maybeCounters.length) {
		maybeCounters.forEach((maybeCounter) => {
			maybeCounter.dataset.count = value

			maybeCounter.innerHTML = messsage
		})
	}
}

const toggleFormVisibility = (el, data) => {
	if (
		!data ||
		data.blocksy_stock_quantity > 0 ||
		(data.is_in_stock && !data.backorders_allowed) ||
		(data.backorders_allowed &&
			ct_localizations.blc_ext_waitlist.waitlist_allow_backorders ===
				'no')
	) {
		el.dataset.state = 'hidden'
	} else {
		if (
			data.blocksy_waitlist.subscription_id ||
			ct_localizations.blc_ext_waitlist.list.includes(data.variation_id)
		) {
			el.dataset.state = 'subscribed'
			const maybeUnsubscribeButton = el.querySelector('.unsubscribe')

			if (maybeUnsubscribeButton) {
				maybeUnsubscribeButton.dataset.token =
					data.blocksy_waitlist.unsubscribe_token
				maybeUnsubscribeButton.dataset.id = data.variation_id
			}

			changeCounter(
				el,
				data.blocksy_waitlist.waitlist_users,
				data.blocksy_waitlist.waitlist_users_message
			)

			return
		}

		changeCounter(
			el,
			data.blocksy_waitlist.waitlist_users,
			data.blocksy_waitlist.waitlist_users_message
		)

		el.dataset.state = 'visible'
	}
}

const updateUnsubscribeButton = (el, data) => {
	const unsubscribeButton = el
		.closest('.ct-product-waitlist')
		.querySelector('.unsubscribe')

	if (unsubscribeButton) {
		unsubscribeButton.dataset.token = data.token
		unsubscribeButton.dataset.id = data.productId
	}
}

const updateTable = (el) => {
	const row = el.closest('.ct-woocommerce-waitlist-table-row')

	if (!row) return

	const numberOfRows = document.querySelectorAll(
		'.ct-woocommerce-waitlist-table-row'
	).length

	if (numberOfRows === 1) {
		window.location.reload()
	} else {
		row.remove()
	}
}

const updateCookie = (value, remove = false) => {
	const {
		blc_ext_waitlist: { user_logged_in, list },
	} = ct_localizations

	if (user_logged_in !== 'no') return

	const newList = remove
		? list.filter((productId) => productId !== value)
		: [...list, value]

	ct_localizations.blc_ext_waitlist.list = newList

	const expires = new Date(Date.now() + COOKIE_LIFETIME).toGMTString()
	document.cookie = `${COOKIE_NAME}=${JSON.stringify(
		newList
	)}; expires=${expires}; path=/`
}

const handleFormSubmit = (el) => {
	const body = new FormData(el)
	body.append('action', 'blc_subcribe_to_waitlist')

	const waitlist = el.closest('.ct-product-waitlist')
	waitlist.dataset.loading = ''

	const productEl = el.closest('.product')
	let productId = productEl.className.match(/post-(\d+)/)?.[1]

	const maybeVariationEl = productEl.querySelector('[name="variation_id"]')

	if (maybeVariationEl && maybeVariationEl.value) {
		productId = maybeVariationEl.value
	}

	body.append('product_id', productId)

	fetch(ct_localizations.ajax_url, {
		method: 'POST',
		body,
	})
		.then((r) => r.json())
		.then(({ success, data }) => {
			if (!success) {
				alert(data.message)
				return
			}

			waitlist.dataset.state = 'subscribed'

			changeCounter(
				waitlist,
				data.waitlist_users,
				data.waitlist_users_message
			)
			updateCookie(data.subscription_id)

			updateUnsubscribeButton(el, {
				token: data.unsubscribe_token,
				productId,
			})
		})
		.finally(() => {
			waitlist.removeAttribute('data-loading')
		})
}

const handleUnsubscribe = (el) => {
	const body = new FormData()
	body.append('action', 'blc_waitlist_unsubscribe')
	body.append('token', el.dataset.token)

	const isAccountAction = el.closest('.waitlist-product-actions')
	const waitlist = isAccountAction || el.closest('.ct-product-waitlist')
	waitlist.dataset.loading = ''

	const productEl = el.closest('.product')
	let productId = ''

	if (productEl) {
		productId = productEl.className.match(/post-(\d+)/)?.[1]

		const maybeVariationEl = productEl.querySelector(
			'[name="variation_id"]'
		)

		if (maybeVariationEl && maybeVariationEl.value) {
			productId = maybeVariationEl.value
		}
	}

	body.append('product_id', productId)

	fetch(ct_localizations.ajax_url, {
		method: 'POST',
		body,
	})
		.then((r) => r.json())
		.then(({ success, data }) => {
			if (!success) {
				alert(data.message)
				return
			}

			waitlist.dataset.state = ''

			changeCounter(
				waitlist,
				data.waitlist_users,
				data.waitlist_users_message
			)
			updateCookie(el.dataset.id, true)
			updateTable(el)
		})
		.finally(() => {
			waitlist.removeAttribute('data-loading')
		})
}

const handleSync = (el) => {
	const productEl = el.closest('.product')
	let productId = productEl.className.match(/post-(\d+)/)?.[1]

	const maybeVariationEl = productEl.querySelector('[name="variation_id"]')

	if (maybeVariationEl && maybeVariationEl.value) {
		productId = maybeVariationEl.value
	}

	const body = new FormData()
	body.append('action', 'blc_waitlist_sync')
	body.append('product_id', productId)

	fetch(ct_localizations.ajax_url, {
		method: 'POST',
		body,
	})
		.then((r) => r.json())
		.then(({ success, data }) => {
			if (!data.subscription_id || !success) {
				return
			}

			toggleFormVisibility(el.closest('.ct-product-waitlist'), {
				blocksy_waitlist: {
					...data,
				},
			})
		})
}

registerDynamicChunk('blocksy_ext_woo_extra_waitlist', {
	mount: (el, rest) => {
		if (!rest) {
			handleSync(el)
			return
		}

		const { event, eventData } = rest

		if (event.type === 'submit') {
			handleFormSubmit(el)

			return
		}

		if (event.type === 'click' && el.classList.contains('unsubscribe')) {
			handleUnsubscribe(el)

			return
		}

		if (event.type === 'reset_data' || event.type === 'found_variation') {
			toggleFormVisibility(el, eventData)
		}
	},
})
