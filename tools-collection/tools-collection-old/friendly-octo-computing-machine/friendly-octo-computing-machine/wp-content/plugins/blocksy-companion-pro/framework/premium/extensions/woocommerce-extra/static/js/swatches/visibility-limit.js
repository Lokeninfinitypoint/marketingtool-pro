export const adjustVisibleItems = (container) => {
	if (!container) {
		return
	}

	const items = container.querySelectorAll('.ct-swatch-container')
	const trigger = container.querySelector('.ct-swatches-more')

	if (!trigger || trigger.dataset.state === 'expanded') {
		return
	}

	const limit = parseInt(trigger.dataset.swatchesLimit, 10)
	if (isNaN(limit) || limit < 0) {
		return
	}

	items.forEach((item) => {
		if (item?.classList?.remove) {
			item.classList.remove('ct-limited')
		}
	})

	const availableItems = Array.from(items).filter(
		(item) => item && !item.classList.contains('ct-hidden')
	)

	availableItems.forEach((item, idx) => {
		if (idx >= limit) {
			item.classList.add('ct-limited')
		}
	})

	if (availableItems.length <= limit) {
		trigger.dataset.state = 'hidden'
	} else {
		trigger.innerText =
			ct_localizations.swatches_data.limit_number_of_swatches_message.replace(
				'{items}',
				availableItems.length - limit
			)
		trigger.dataset.state = 'collapsed'
	}
}

export const expandSwatches = (trigger) => {
	if (!trigger) {
		return
	}

	const container = trigger.closest('.ct-variation-swatches')
	if (!container) {
		return
	}

	const items = container.querySelectorAll('.ct-swatch-container')

	items.forEach((item) => {
		item.classList.remove('ct-limited')
	})

	trigger.dataset.state = 'expanded'
}
