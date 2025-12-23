import { registerDynamicChunk } from 'blocksy-frontend'

import { maybeHandleArchiveProduct } from './favorite-variations/archive'
import { maybeHandleSingleProduct } from './favorite-variations/single'

registerDynamicChunk('blocksy_ext_woo_extra_variations_wish_list', {
	mount: (el, { event, eventData }) => {
		const data = {
			el,
			type: event.type,
			eventData,
		}
		maybeHandleSingleProduct(data)
		maybeHandleArchiveProduct(data)
	},
})
