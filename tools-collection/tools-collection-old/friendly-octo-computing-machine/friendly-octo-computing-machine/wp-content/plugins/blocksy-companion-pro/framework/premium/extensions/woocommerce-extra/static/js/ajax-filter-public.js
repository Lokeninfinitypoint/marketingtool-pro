import { registerDynamicChunk } from 'blocksy-frontend'
import ctEvents from 'ct-events'

import { patchCurrentPageWith } from './filters/patch-current-page'
import { scrollToTarget } from '../../../../../../static/js/helpers/scroll-to-target'

const store = {}

const makeSilentRedirect = (url) => {
	const newUrl = new URL(url)

	newUrl.searchParams.delete('blocksy_ajax')

	window.history.replaceState(null, '', newUrl.toString())
}

export const cachedFetch = (url, silent_redirect = false) =>
	store[url]
		? new Promise((resolve) => {
				resolve(store[url])

				const isSilent = store[url].silent_redirect

				if (isSilent) {
					makeSilentRedirect(store[url].url)
				}

				if (!window.ct_customizer_localizations) {
					store[url] = store[url].clone()
					store[url].silent_redirect = isSilent
				}
		  })
		: new Promise((resolve) =>
				fetch(url).then((response) => {
					if (response.redirected) {
						if (silent_redirect) {
							makeSilentRedirect(response.url)
						} else {
							window.location.replace(response.url)

							return
						}
					}

					resolve(response)

					if (!window.ct_customizer_localizations) {
						store[url] = response.clone()
						store[url].silent_redirect = silent_redirect
					}
				})
		  )

export const beforeRequest = (fromCache) => {
	return new Promise((resolve) => {
		let products = document.querySelector('.ct-products-container')

		if (
			document.querySelector('[data-ajax-filters*="scroll"]') &&
			products &&
			products.closest('.ct-container') &&
			products.closest('.ct-container').getBoundingClientRect().top < 0
		) {
			scrollToTarget(products.closest('.ct-container'))
		}

		const loading = document.querySelector('.ct-filters-loading')

		if (!fromCache && loading) {
			loading.classList.add('active')
		}

		products.dataset.animate = 'leave:start'

		requestAnimationFrame(() => {
			products.dataset.animate = 'leave:end'

			const itemWithTransition = [...products.children].find((c) =>
				c.matches('[data-products], .woocommerce-no-products-found')
			)

			whenTransitionEnds(itemWithTransition, () => {
				products.dataset.animate = 'leave'
				resolve()
			})
		})

		const panel = document.querySelector('#woo-filters-panel')

		if (
			panel &&
			'closeOnSelect' in panel.dataset &&
			panel.classList.contains('active')
		) {
			const toggleEl = document.querySelector(
				'[data-toggle-panel="#woo-filters-panel"]'
			)

			if (toggleEl) {
				toggleEl.click()
			}
		}
	})
}

export const afterRequest = () => {
	const loading = document.querySelector('.ct-filters-loading')

	if (!loading) {
		return
	}

	const mount = () => {
		let products = document.querySelector('.ct-products-container')

		products.dataset.animate = 'appear:start'

		requestAnimationFrame(() => {
			products.dataset.animate = 'appear:end'

			const itemWithTransition = [...products.children].find((c) =>
				c.matches('[data-products], .woocommerce-no-products-found')
			)

			whenTransitionEnds(itemWithTransition, () => {
				products.removeAttribute('data-animate')
			})
		})

		ctEvents.trigger('blocksy:frontend:init')
	}

	if (loading.classList.contains('active')) {
		loading.classList.remove('active')

		whenTransitionEnds(loading, () => {
			mount()
		})
	} else {
		mount()
	}
}

const updateQueryParams = (uri) => {
	const url = new URL(uri, location.href)

	url.searchParams.delete('blocksy_ajax')

	uri = url.toString()

	// searchParams.forEach((value, key) => {
	// 	if (!value) {
	// 		searchParams.delete(key)
	// 	}
	// })

	// const newUlr = searchParams.toString().length
	// 	? decodeURIComponent(searchParams.toString())
	// 	: window.location.pathname

	window.history.pushState(null, document.title, uri)
}

export const fetchData = (uri, silent_redirect) =>
	new Promise((resolve) => {
		cachedFetch(uri, silent_redirect)
			.then((res) => res.text())
			.then((data, ...a) => {
				patchCurrentPageWith(data)
				resolve()
			})
	})

export const fetchDataFor = (url, silent_redirect) => {
	const fromCache = !!store[url]

	beforeRequest(fromCache).then(() => {
		fetchData(url, silent_redirect).then(() => {
			setTimeout(() => {
				afterRequest()
			}, 50)
		})
	})
}

registerDynamicChunk('blocksy_ext_woo_extra_ajax_filters', {
	mount: (el, { event }) => {
		if (event.type === 'popstate') {
			fetchDataFor(window.location.href)
			return
		}

		const isAjax = document.querySelector('[data-ajax-filters*="yes"]')

		if (
			el.tagName === 'INPUT' &&
			el.type === 'checkbox' &&
			event.type === 'change'
		) {
			const link = el.closest('.ct-filter-item').querySelector('a')

			if (!isAjax) {
				window.location.href = link.getAttribute('href')

				return
			}

			el = link
		}

		if (el.tagName === 'A') {
			const maybeParent = el.closest('.ct-filter-item')

			if (maybeParent) {
				if (maybeParent.classList.contains('active')) {
					maybeParent.classList.remove('active')
				} else {
					maybeParent.classList.add('active')
				}
			}

			if (el.closest('.ct-filter-item')) {
				const maybeCheckbox = el
					.closest('.ct-filter-item')
					.querySelector('[type="checkbox"]')

				if (maybeCheckbox) {
					if (maybeCheckbox.getAttribute('checked')) {
						maybeCheckbox.checked = false
						maybeCheckbox.removeAttribute('checked')
					} else {
						maybeCheckbox.checked = true
						maybeCheckbox.setAttribute('checked', 'checked')
					}

					const newCheckbox = maybeCheckbox.cloneNode(true)
					maybeCheckbox.parentNode.replaceChild(
						newCheckbox,
						maybeCheckbox
					)
				}
			}

			if (!isAjax) {
				window.location.href = el.getAttribute('href')

				return
			}
		}

		if (el.tagName === 'FORM') {
			return
		}

		if (!isAjax) {
			return
		}

		if (el.tagName === 'SELECT' && el.closest('.woocommerce-ordering')) {
			const url = new URL(window.location.href)
			const formData = new FormData(el.closest('.woocommerce-ordering'))

			;[...formData.entries()].map(([key, value]) => {
				if (key !== 'paged') {
					url.searchParams.set(key, value)
				}
			})

			const searchParams = new URLSearchParams(url.search)
			searchParams.set('blocksy_ajax', 'yes')

			const requestUrl = `${url.origin}${
				url.pathname
			}?${searchParams.toString()}`

			updateQueryParams(requestUrl)
			fetchDataFor(requestUrl)

			return
		}

		let requestUrl = el.getAttribute('href')
		let silent_redirect = false

		if (el.classList.contains('page-numbers')) {
			const url = new URL(requestUrl)
			const searchParams = new URLSearchParams(url.search)

			searchParams.set('blocksy_ajax', 'yes')

			requestUrl = `${url.origin}${
				url.pathname
			}?${searchParams.toString()}`

			silent_redirect = true
		}

		updateQueryParams(requestUrl)
		fetchDataFor(requestUrl, silent_redirect)
	},
})

function whenTransitionEnds(el, cb) {
	const end = () => {
		el.removeEventListener('transitionend', onEnd)
		cb()
	}

	const onEnd = (e) => {
		if (e.target === el) {
			end()
		}
	}

	el.addEventListener('transitionend', onEnd)
}
