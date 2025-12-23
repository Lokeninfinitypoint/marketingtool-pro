export const getPastPopups = () => {
	const received = JSON.parse(localStorage.getItem('blocksyPastPopups'))

	let result = {}

	if (Array.isArray(received)) {
		received.forEach((key) => {
			result[key] = {
				closed: {
					reason: 'cancel',
					timestamp: new Date().getTime(),
				},
				pages: [],
			}
		})
	} else {
		result = received
	}

	result = Object.keys(result || {}).reduce((acc, key) => {
		const { isExpired, ...newPopup } = result[key]

		return {
			...acc,
			[key]: {
				...newPopup,
				...(newPopup.closed
					? {}
					: {
							closed: isExpired
								? {
										reason: 'cancel',
										timestamp: new Date().getTime(),
								  }
								: null,
					  }),
			},
		}
	}, {})

	return result || {}
}

export const isPopupExpired = (popup) => {
	let popupId = popup.id.replace('ct-popup-', '')

	const maybePastPopupDescriptor = getPastPopups()[popupId]

	if (popup.dataset.popupRelaunch) {
		if (popup.dataset.popupRelaunch === 'always') {
			return false
		}

		if (
			popup.dataset.popupRelaunch.indexOf('custom') > -1 &&
			maybePastPopupDescriptor
		) {
			const popupRelaunchDescriptor =
				popup.dataset.popupRelaunch.split(':')

			let minutesAfterCancel = 0
			let minutesAfterSuccess = 0

			if (popupRelaunchDescriptor.length > 1) {
				minutesAfterCancel = parseInt(popupRelaunchDescriptor[1])
				minutesAfterSuccess = minutesAfterCancel
			}

			if (popupRelaunchDescriptor.length > 2) {
				minutesAfterSuccess = parseInt(popupRelaunchDescriptor[2])
			}

			const { closed } = maybePastPopupDescriptor

			if (!closed || !closed.timestamp) {
				return false
			}

			const minutes =
				closed.reason === 'cancel'
					? minutesAfterCancel
					: minutesAfterSuccess
			const days = minutes / 60 / 24

			const diffInMs = new Date() - new Date(closed.timestamp)
			const diffInDays = diffInMs / (1000 * 60 * 60 * 24)

			if (diffInDays > days) {
				return false
			}

			return true
		}
	}

	return maybePastPopupDescriptor && maybePastPopupDescriptor.closed
}
