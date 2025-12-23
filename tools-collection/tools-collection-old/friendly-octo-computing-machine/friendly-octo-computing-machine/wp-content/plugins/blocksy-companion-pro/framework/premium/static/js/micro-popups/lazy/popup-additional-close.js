import { closeMicroPopup } from './open-close-logic'
import $ from 'jquery'

export const mountAdditionalCloseForPopup = (popup, args = {}) => {
	args = {
		maybeCloseStrategies: {},

		...args,
	}

	if (args.maybeCloseStrategies.button_click) {
		const maybeDelay =
			parseInt(args.maybeCloseStrategies.button_click.close_delay || 0) *
			1000

		const selector = args.maybeCloseStrategies.button_click.selector

		const maybeButtons = popup.querySelectorAll(selector)

		if (maybeButtons.length) {
			maybeButtons.forEach((button) => {
				if (!button.hasAdditionalCloseEvent) {
					button.hasAdditionalCloseEvent = true

					button.addEventListener('click', (e) => {
						e.preventDefault()
						closeMicroPopup(popup, {
							reason: 'button_click',
							delay: maybeDelay,
						})
					})
				}
			})
		}
	}

	if (args.maybeCloseStrategies.form_submit) {
		const maybeForm = popup.querySelector('form')

		const maybeDelay =
			parseInt(args.maybeCloseStrategies.form_submit.close_delay || 0) *
			1000

		if (maybeForm && !maybeForm.hasAdditionalCloseEvent) {
			maybeForm.hasAdditionalCloseEvent = true

			// Check for Kadence Form.
			window.document.body.addEventListener(
				'kb-form-success',
				() => {
					closeMicroPopup(popup, {
						reason: 'form_submit:kadence',
						delay: maybeDelay,
					})
				},
				false
			)

			if (maybeForm.matches('.wpforms-form')) {
				$(maybeForm).on('wpformsAjaxSubmitSuccess', () => {
					closeMicroPopup(popup, {
						reason: 'form_submit:wpforms',
						delay: maybeDelay,
					})
				})
			} else if (maybeForm.matches('form.frm-fluent-form')) {
				$(maybeForm).on('fluentform_submission_success', function (e) {
					closeMicroPopup(popup, {
						reason: 'form_submit:fluentform',
						delay: maybeDelay,
					})
				})
			} else if (
				maybeForm.matches('.gform_anchor') ||
				maybeForm.querySelector('[class*="gform_"]')
			) {
				var form_id = maybeForm.id
				form_id = parseInt(form_id.replace(/\D/g, ''))

				jQuery(document).on(
					'gform_confirmation_loaded',
					(event, formId) => {
						if (form_id !== formId) {
							return
						}

						closeMicroPopup(popup, {
							reason: 'form_submit:gravityforms',
							delay: maybeDelay,
						})
					}
				)
			} else if (
				maybeForm.querySelector('.ninja-forms-field[type="submit"]')
			) {
				jQuery(document).on(
					'nfFormSubmitResponse',
					(event, { response }) => {
						if (!response.errors.length) {
							closeMicroPopup(popup, {
								reason: 'form_submit:ninjaforms',
								delay: maybeDelay,
							})
						}
					}
				)
			} else {
				maybeForm.addEventListener('submit', (e) => {
					e.preventDefault()
					// TODO: maybe even allow form validation sometime in the future?
					closeMicroPopup(popup, {
						reason: 'form_submit:default',
						delay: maybeDelay,
					})
				})
			}
		}
	}
}
