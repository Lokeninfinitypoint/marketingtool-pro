import { registerDynamicChunk } from 'blocksy-frontend'

const getContentFor = (menuId, ids) => {
	return new Promise((resolve) => {
		const localData = localStorage.getItem(
			blocksyMegaMenu.persistence_key + ':' + menuId
		)

		if (localData) {
			const maybeData = JSON.parse(localData)

			if (
				maybeData &&
				ids.every((id) => maybeData.find((chunk) => chunk.id === id))
			) {
				resolve(JSON.parse(localData))
				return
			}
		}

		const body = new FormData()

		body.append('ids', ids.join(','))
		body.append('menu_id', menuId)

		fetch(
			`${ct_localizations.ajax_url}?action=blc_retrieve_mega_menu_content`,
			{
				method: 'POST',
				body,
			}
		)
			.then((response) => response.json())
			.then(({ success, data }) => {
				if (!success) {
					return
				}

				if (!data) {
					return
				}

				const [
					persistenceKeyFirst,
					persistenceKeySecond,

					// This is the third key that is the dynamic portion of
					// persistence_key. It is not relevant to us.
					persistinceKeyThird,
				] = blocksyMegaMenu.persistence_key.split(':')

				const keysToRemove = []

				for (let i = 0; i < localStorage.length; i++) {
					const key = localStorage.key(i)

					if (
						key.indexOf(
							[persistenceKeyFirst, persistenceKeySecond].join(
								':'
							)
						) !== 0
					) {
						continue
					}

					const [
						localStorageKeyFirst,
						localStorageKeySecond,
						localStorageKeyThird,
						localStorageKeyFourth,
					] = key.split(':')

					if (
						persistenceKeyFirst === localStorageKeyFirst &&
						persistenceKeySecond === localStorageKeySecond &&
						localStorageKeyFourth === menuId
					) {
						keysToRemove.push(key)
					}
				}

				keysToRemove.map((key) => {
					localStorage.removeItem(key)
				})

				localStorage.setItem(
					blocksyMegaMenu.persistence_key + ':' + menuId,
					JSON.stringify(data.content)
				)

				resolve(data.content)
			})
	})
}

registerDynamicChunk('blocksy_mega_menu', {
	mount: (els) => {
		const ids = els
			.filter((el) => el.closest('ul'))
			.map((el) =>
				parseFloat(
					[...el.parentNode.classList]
						.find((cls) => cls.match(/^menu-item-\d{1,}/))
						.replace('menu-item-', '')
				)
			)

		getContentFor(
			els[0].closest('ul[id^="menu-"]').id.replace('menu-', ''),
			ids
		).then((data) => {
			els.filter((el) => el.closest('ul')).map((el) => {
				const id = parseFloat(
					[...el.parentNode.classList]
						.find((cls) => cls.match(/^menu-item-\d{1,}/))
						.replace('menu-item-', '')
				)

				const content = data.find((chunk) => chunk.id === id)

				if (!content) {
					return
				}

				const temporaryBuffer = document.createElement('div')

				temporaryBuffer.innerHTML = el.innerHTML

				const newMenu = document.createElement('div')
				newMenu.innerHTML = content.content

				el.innerHTML = newMenu.firstElementChild.innerHTML
				el.className = newMenu.firstElementChild.className
				;[...el.querySelectorAll('.menu-item')].map((menuItem) => {
					const maybeOriginal = temporaryBuffer.querySelector(
						`#${menuItem.id}`
					)

					if (!maybeOriginal) {
						return
					}

					if (
						menuItem.firstElementChild &&
						maybeOriginal.firstElementChild &&
						maybeOriginal.matches('.ct-menu-link')
					) {
						menuItem.firstElementChild.innerHTML =
							maybeOriginal.firstElementChild.innerHTML
					}

					if (
						menuItem.firstElementChild &&
						maybeOriginal.firstElementChild &&
						maybeOriginal.firstElementChild.matches('.ct-menu-link')
					) {
						menuItem.firstElementChild.outerHTML =
							maybeOriginal.firstElementChild.outerHTML
					}

					menuItem.className = maybeOriginal.className
				})
			})

			ctEvents.trigger('blocksy:frontend:init')
		})
	},
})
