const exportUsers = async () => {
	const body = new FormData()
	body.append('action', 'blocksy_ext_waitlist_export_users')

	body.append(
		'nonce',
		(
			window.ctDashboardLocalizations ||
			window.ct_localizations ||
			window.ct_customizer_localizations
		).dashboard_actions_nonce
	)

	const searchParams = new URLSearchParams(window.location.search)

	if (searchParams.has('product_id')) {
		body.append('product_id', searchParams.get('product_id'))
	}

	if (searchParams.has('variation_id')) {
		body.set('product_id', searchParams.get('variation_id'))
	}

	try {
		const response = await fetch(ajaxurl, {
			method: 'POST',
			body,
		})

		if (response.status === 200) {
			const body = await response.json()

			if (body.success) {
				if (body.data.url) {
					window.open(body.data.url).focus()
				}
			}
		}
	} catch (e) {}
}

document.addEventListener('DOMContentLoaded', () => {
	const exportButton = document.querySelector('.ct-waitlist-export')

	if (!exportButton) {
		return
	}

	exportButton.addEventListener('click', (e) => {
		e.preventDefault()

		exportUsers()
	})
})
