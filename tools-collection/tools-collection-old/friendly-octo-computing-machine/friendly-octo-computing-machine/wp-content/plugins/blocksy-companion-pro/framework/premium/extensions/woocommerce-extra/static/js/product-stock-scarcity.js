import { registerDynamicChunk } from 'blocksy-frontend'

const initScarcity = (maxQty, lowStockAmount, scarcityEl) => {
	const scarcityMessage = scarcityEl.querySelector('.ct-message')

	scarcityEl.dataset.items = maxQty || 0
	const itemsEl = scarcityEl.querySelector('.ct-stock-quantity')

	if (maxQty === 0 || !lowStockAmount || !itemsEl) {
		scarcityEl.hidden = true
		return
	}

	itemsEl.innerHTML = itemsEl.innerHTML.replace(/\d+/, maxQty)

	if (scarcityEl) {
		let persent = Math.min((maxQty / lowStockAmount) * 100, 100)

		if (persent >= 100) {
			scarcityEl.hidden = true
		} else {
			scarcityEl.removeAttribute('hidden')
		}

		scarcityEl.querySelector(
			'.ct-progress-bar span'
		).style.width = `${persent}%`
	}
}

registerDynamicChunk('blocksy_ext_woo_extra_stock_scarcity', {
	mount: (element, { event, eventData }) => {
		if (event.type === 'reset_data' || event.type === 'found_variation') {
			initScarcity(
				eventData?.max_qty || 0,
				eventData?.blocksy_low_stock_amount,
				element
			)
		}
	},
})
