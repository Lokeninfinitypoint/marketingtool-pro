import { registerDynamicChunk } from 'blocksy-frontend'

registerDynamicChunk('blocksy_ext_woo_extra_filters_lookup_progress', {
	mount: (el, { event }) => {
		const url = new URL(ct_localizations.ajax_url)
		url.searchParams.append('action', 'blc_get_lookup_progress')

		let intervalId

		const fetchProgress = () => {
			try {
				fetch(url)
					.then((r) => r.json())
					.then(({ success, data }) => {
						if (!success) {
							return
						}

						const percentMessages = document.querySelectorAll(
							'.ct-filter-widget-wrapper .ct-notice-progress-bar-heading span'
						)
						const progressBars = document.querySelectorAll(
							'.ct-filter-widget-wrapper .ct-notice-progress-bar span'
						)

						if (data.state === 'progress') {
							const percent =
								data.total_count > 0
									? Math.round(
											(data.processed_count /
												data.total_count) *
												100
									  )
									: 0

							percentMessages.forEach((el) => {
								el.innerText = `${percent}%`
							})

							progressBars.forEach((bar) => {
								bar.style.width = `${percent}%`
							})
						}

						if (data.state === 'idle' && data.enabled === true) {
							clearInterval(intervalId)

							percentMessages.forEach((el) => {
								el.innerText = `100%`
							})

							progressBars.forEach((bar) => {
								bar.style.width = `100%`
							})

							setTimeout(() => {
								window.location.reload()
							}, 1000)
						}
					})
			} catch (error) {
				console.error('Error fetching lookup progress:', error)
			}
		}

		fetchProgress()
		intervalId = setInterval(fetchProgress, 5000)

		return () => clearInterval(intervalId)
	},
})
