import { registerDynamicChunk } from 'blocksy-frontend'

const icon =
	'<svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24"><path d="M6.14 23.163c0 .457-.38.837-.838.837H4.186A4.188 4.188 0 0 1 0 19.814v-1.116c0-.458.38-.838.837-.838s.837.38.837.838v1.116a2.515 2.515 0 0 0 2.512 2.512h1.116c.458 0 .838.38.838.837ZM19.814 0h-1.116c-.458 0-.838.38-.838.837s.38.837.838.837h1.116a2.515 2.515 0 0 1 2.512 2.512v1.116c0 .458.38.838.837.838S24 5.76 24 5.302V4.186A4.188 4.188 0 0 0 19.814 0ZM.837 6.14c.458 0 .837-.38.837-.838V4.186a2.515 2.515 0 0 1 2.512-2.512h1.116c.458 0 .838-.38.838-.837S5.76 0 5.302 0H4.186A4.188 4.188 0 0 0 0 4.186v1.116c0 .458.38.838.837.838Zm22.326 11.72a.843.843 0 0 0-.837.838v1.116a2.515 2.515 0 0 1-2.512 2.512h-1.116c-.458 0-.838.38-.838.837s.38.837.838.837h1.116A4.188 4.188 0 0 0 24 19.814v-1.116a.843.843 0 0 0-.837-.838Zm0-8.93a.843.843 0 0 0-.837.837v4.466c0 .457.38.837.837.837s.837-.38.837-.837V9.767a.843.843 0 0 0-.837-.837ZM.837 15.07c.458 0 .837-.38.837-.837V9.767c0-.457-.38-.837-.837-.837S0 9.31 0 9.767v4.466c0 .457.38.837.837.837Zm13.396 7.256H9.767c-.457 0-.837.38-.837.837s.38.837.837.837h4.466c.457 0 .837-.38.837-.837s-.38-.837-.837-.837ZM9.767 1.674h4.466c.457 0 .837-.38.837-.837S14.69 0 14.233 0H9.767C9.31 0 8.93.38 8.93.837s.38.837.837.837ZM6.084 7.892l4.096 10.203c.213.536.715.87 1.295.87h.045a1.394 1.394 0 0 0 1.284-.948l1.306-3.918 3.918-1.306c.558-.19.938-.693.949-1.284a1.382 1.382 0 0 0-.871-1.34L7.903 6.073c-.524-.213-1.116-.09-1.507.312s-.513.982-.312 1.507Z"/></svg>'

const generateMenu = (placeholder, items) => {
	const wrapper = placeholder.closest('.ab-submenu')

	if (!wrapper) {
		return
	}

	if (!items || !Object.keys(items).length) {
		wrapper.closest('.menupop').remove()
		return
	}

	wrapper.closest('.menupop').classList.remove('ct-hidden')

	let markup = ''

	for (const [key, value] of Object.entries(items)) {
		markup += `<li role="group" id="wp-admin-bar-blocksy-content-blocks_${
			value.id
		}"><a class="ab-item" role="menuitem" href="${value.href}">${
			value.title
		} ${
			value.type !== 'popup'
				? `<span class="ct-scroll-to" data-id="${value.type}:${key}">${icon}</span>`
				: ''
		}</a></li>`
	}

	wrapper.innerHTML = markup
}

const createMarkup = () => {
	const data = window.blocksyContentBlocks

	if (!data) {
		return
	}

	const hooksPlaceholder = document.getElementById(
		'wp-admin-bar-blocksy-content-blocks_hooks_placeholder'
	)
	const popupsPlaceholder = document.getElementById(
		'wp-admin-bar-blocksy-content-blocks_popups_placeholder'
	)
	const templatesPlaceholder = document.getElementById(
		'wp-admin-bar-blocksy-content-blocks_templates_placeholder'
	)

	if (hooksPlaceholder) {
		generateMenu(hooksPlaceholder, data.hooks)
	}

	if (popupsPlaceholder) {
		generateMenu(popupsPlaceholder, data.popups)
	}

	if (templatesPlaceholder) {
		generateMenu(templatesPlaceholder, data.templates)
	}
}

const addListeners = () => {
	const triggers = document.querySelectorAll('.ct-scroll-to')

	if (!triggers.length) {
		return
	}

	triggers.forEach((trigger) => {
		trigger.closest('.ab-item').addEventListener('mouseenter', (e) => {
			const id = trigger.dataset.id
			const els = document.querySelectorAll(`[data-block*="${id}"]`)

			if (!id || !els.length) {
				return
			}

			els.forEach((el) => {
				el.dataset.block += ':highlight'
			})
		})

		trigger.closest('.ab-item').addEventListener('mouseleave', (e) => {
			const id = trigger.dataset.id
			const els = document.querySelectorAll(`[data-block*="${id}"]`)

			if (!id || !els.length) {
				return
			}

			els.forEach((el) => {
				el.dataset.block = el.dataset.block.replace(':highlight', '')
			})
		})

		trigger.addEventListener('click', (e) => {
			e.preventDefault()

			const id = trigger.dataset.id
			const els = document.querySelectorAll(`[data-block*="${id}"]`)

			if (!id || !els.length) {
				return
			}

			const headerHeight =
				document
					.querySelector(
						'[data-header*="sticky"] .ct-sticky-container'
					)
					?.getBoundingClientRect()?.height || 50

			window.scrollTo({
				top: els[0].offsetTop - headerHeight - 50,
				behavior: 'smooth',
			})

			els.forEach((el) => {
				el.dataset.block += ':highlight'
			})

			setTimeout(() => {
				els.forEach((el) => {
					el.dataset.block = el.dataset.block.replace(
						':highlight',
						''
					)
				})
			}, 2000)
		})
	})
}

const mountHookIndicators = (el, event) => {
	event.preventDefault()

	const name = `Hook ${el.dataset.hook.split('::')[0]}`

	fetch(
		`${ct_localizations.ajax_url}?action=blocksy_content_blocksy_create&name=${name}&type=hook&predefined_hook=${el.dataset.hook}`
	)
		.then((r) => r.json())
		.then(({ data: { url } }) => {
			window.location = url
		})
}

let hasEventListeners = false

registerDynamicChunk('blocksy_ext_content_blocks_navigation', {
	mount: (el, { event }) => {
		if (event.type === 'click') {
			mountHookIndicators(el, event)
			return
		}

		if (hasEventListeners) {
			return
		}

		hasEventListeners = true

		createMarkup()
		addListeners()
	},
})
