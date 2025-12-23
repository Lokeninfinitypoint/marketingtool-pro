export const mountHooksSwitch = () => {
	const container = document.querySelector(
		'.post-type-ct_content_block .wp-list-table'
	)

	if (!container) {
		return
	}

	container.addEventListener('click', (e) => {
		let maybeSwitch = null

		if (e.target.classList.contains('ct-content-block-switch')) {
			maybeSwitch = e.target
		}

		if (!maybeSwitch) {
			maybeSwitch = e.target.closest('.ct-content-block-switch')
		}

		if (!maybeSwitch) {
			return
		}

		e.preventDefault()

		const postId = maybeSwitch.dataset.postId

		let enabled = maybeSwitch.classList.contains('ct-active') ? 'no' : 'yes'

		maybeSwitch.classList.toggle('ct-active')

		fetch(
			`${ct_localizations.ajax_url}?action=blocksy_content_blocksy_toggle&post_id=${postId}&enabled=${enabled}`
		).then((r) => r.json())
	})
}
