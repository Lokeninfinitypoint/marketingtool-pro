import { registerDynamicChunk } from 'blocksy-frontend'
import cachedFetch from 'ct-wordpress-helpers/cached-fetch'

const WOO_EVENTS = [
	'added_to_cart',
	'removed_from_cart',
	'wc_fragments_refreshed',
	'updated_cart_totals',
]

const counters = new WeakMap()

const getDateObj = (date) => {
	let utcDate = date
	if (!date.endsWith('Z')) {
		utcDate = date.replace(' ', 'T') + 'Z'
	}

	const expirationTime = new Date(utcDate).getTime()

	const now = Date.now()

	let remaining = Math.floor((expirationTime - now) / 1000)
	if (remaining < 0) remaining = 0

	const minutes = Math.floor(remaining / 60)
	const seconds = remaining % 60

	return { minutes, seconds, remaining, expirationTime }
}

const startCountDown = (wrapper, date) => {
	if (counters.has(wrapper)) {
		clearInterval(counters.get(wrapper))
		counters.delete(wrapper)
	}

	let timer = wrapper.querySelector('time[datetime]')
	if (!timer) {
		timer = wrapper.querySelector('time[datetime]')
	}

	const interval = setInterval(() => {
		const { minutes, seconds, remaining } = getDateObj(date)

		if (!timer.isConnected) {
			clearInterval(interval)
			counters.delete(wrapper)
			return
		}

		timer.querySelector('span:nth-of-type(1)').textContent =
			`${minutes}`.padStart(2, '0')
		timer.querySelector('span:nth-of-type(2)').textContent =
			`${seconds}`.padStart(2, '0')

		if (remaining <= 1) {
			clearInterval(interval)
			counters.delete(wrapper)
			setTimeout(() => {
				timer.remove()
				window.location.reload()
			}, 2000)
		}
	}, 1000)

	counters.set(wrapper, interval)
}

const removeCountDown = (wrapper) => {
	if (!wrapper || !counters.has(wrapper)) {
		return
	}

	clearInterval(counters.get(wrapper))
	counters.delete(wrapper)
}

const initCountDowns = () => {
	document
		.querySelectorAll('[class*="ct-cart-reserved-timer"]')
		.forEach((element) => {
			const countDown = element.querySelector('[datetime]')
			if (!countDown) return

			const { remaining } = getDateObj(countDown.getAttribute('datetime'))
			if (remaining <= 0) {
				removeCountDown(element)
				return
			}

			startCountDown(element, countDown.getAttribute('datetime'))
		})
}

const handleCachedSync = () => {
	let url = new URL(ct_localizations.ajax_url)
	let params = new URLSearchParams(url.search.slice(1))

	params.append('action', 'blc_ext_cart_reserved_timer_sync')

	url.search = `?${params.toString()}`
	cachedFetch(url.toString())
		.then((response) => response.json())
		.then(({ success, data }) => {
			if (!success) {
				return
			}

			const maybeCart =
				document.querySelector(
					'.ct-cart-reserved-timer-placeholder-cart'
				) || document.querySelector('.ct-cart-reserved-timer-cart')
			const maybeMiniCart =
				document.querySelector(
					'.ct-cart-reserved-timer-placeholder-mini-cart'
				) || document.querySelector('.ct-cart-reserved-timer-mini-cart')

			if (!maybeCart && !maybeMiniCart) {
				return
			}

			if (maybeCart && data.cart) {
				maybeCart.outerHTML = data.cart
			}

			if (maybeMiniCart && data.mini_cart) {
				maybeMiniCart.outerHTML = data.mini_cart
			}

			initCountDowns()
		})
}

registerDynamicChunk('blocksy_ext_woo_extra_cart_reserved_timer', {
	mount: (els, args) => {
		args = {
			event: null,

			...args,
		}

		const { event } = args

		if (!event) {
			handleCachedSync()

			return
		}

		const isWooEvent = event?.type && WOO_EVENTS.includes(event.type)

		if (isWooEvent) {
			document
				.querySelectorAll('[class*="ct-cart-reserved-timer"]')
				.forEach((element) => {
					removeCountDown(element)

					const timeout = parseInt(element.dataset.timeout, 10) * 60

					if (timeout) {
						const now = new Date()
						now.setSeconds(now.getSeconds() + timeout)

						const expiration = now.toISOString()
						element
							.querySelector('time[datetime]')
							.setAttribute('datetime', expiration)
					}
				})

			initCountDowns()
		} else {
			initCountDowns()
		}
	},
})
