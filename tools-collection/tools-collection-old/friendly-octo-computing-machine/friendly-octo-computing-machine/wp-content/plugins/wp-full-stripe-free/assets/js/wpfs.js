jQuery.noConflict();
( function ( $ ) {
	'use strict';

	$( function () {
		const FORM_TYPE_INLINE_PAYMENT = 'inline_payment';
		const FORM_TYPE_CHECKOUT_PAYMENT = 'checkout_payment';
		const FORM_TYPE_INLINE_SUBSCRIPTION = 'inline_subscription';
		const FORM_TYPE_CHECKOUT_SUBSCRIPTION = 'checkout_subscription';
		const FORM_TYPE_INLINE_SAVE_CARD = 'inline_save_card';
		const FORM_TYPE_CHECKOUT_SAVE_CARD = 'checkout_save_card';
		const FORM_TYPE_INLINE_DONATION = 'inline_donation';
		const FORM_TYPE_CHECKOUT_DONATION = 'checkout_donation';
		const FROM_TYPE_DOM = 'wpfs-form-type';

		const PAYMENT_TYPE_LIST_OF_AMOUNTS = 'list_of_amounts';
		const PAYMENT_TYPE_CUSTOM_AMOUNT = 'custom_amount';
		const PAYMENT_TYPE_SPECIFIED_AMOUNT = 'specified_amount';
		const PAYMENT_TYPE_CARD_CAPTURE = 'card_capture';
		const AMOUNT_OTHER = 'other';

		const FIELD_DESCRIPTOR_MACRO_FIELD_ID = '{fieldId}';
		const MACRO_SUBMIT_BUTTON_CAPTION_AMOUNT = '{{amount}}';
		const ERROR_MESSAGE_FIELD_CLASS = 'wpfs-form-error-message';

		const SELECTOR_STYLE_RADIO_BUTTONS = 'radio-buttons';
		const SELECTOR_STYLE_DROPDOWN = 'dropdown';
		const SELECTOR_STYLE_BUTTON_GROUP = 'button-group';

		const COUNTRY_CODE_UNITED_STATES = 'US';

		const PAYMENT_DETAIL_ROW_SETUP_FEE = 'setupFee';
		const PAYMENT_DETAIL_ROW_PRODUCT = 'product';
		const PAYMENT_DETAIL_ROW_DISCOUNT = 'discount';
		const PAYMENT_DETAIL_ROW_TAX = 'tax';
		const PAYMENT_DETAIL_ROW_TAX_0 = 'tax-0';
		const PAYMENT_DETAIL_ROW_TAX_1 = 'tax-1';
		const PAYMENT_DETAIL_ROW_TOTAL = 'total';

		const TAX_RATE_TYPE_NO_TAX = 'taxRateNoTax';
		const TAX_RATE_TYPE_FIXED = 'taxRateFixed';
		const TAX_RATE_TYPE_DYNAMIC = 'taxRateDynamic';
		const TAX_RATE_TYPE_STRIPE_TAX = 'taxRateStripeTax';

		const PRICE_LINE_ITEM_TYPE_PRODUCT = 'product';
		const PRICE_LINE_ITEM_TYPE_SETUP_FEE = 'setupFee';

		const PRICE_LINE_ITEM_SUBTYPE_DISCOUNT = 'discount';
		const PRICE_LINE_ITEM_SUBTYPE_TAX = 'tax';

		const PRICE_ID_CUSTOM_AMOUNT = 'customAmount';

		const INTERVAL_DAY = 'day';
		const INTERVAL_WEEK = 'week';
		const INTERVAL_MONTH = 'month';
		const INTERVAL_YEAR = 'year';

		const POSTAL_CODE_PLACEHOLDERS = {
			CA: 'A1B 2C3',
			US: '12345-6789',
		};

		const debugLog = false;

		let stripe = null;
		try {
			//noinspection JSUnresolvedVariable
			if (
				wpfsFormSettings.stripeAccountId !== null &&
				wpfsFormSettings.stripeAccountId.trim() !== ''
			) {
				stripe = Stripe( wpfsFormSettings.stripeKey, {
					stripeAccount: wpfsFormSettings.stripeAccountId,
				} );
			} else {
				stripe = Stripe( wpfsFormSettings.stripeKey );
			}
		} catch ( err ) {
			var $form = $( 'form.wpfs-form:first' );
			const message = vsprintf(
				Boolean( wpfsFormSettings.isAdmin )
					? Boolean( wpfsFormSettings.isConnected )
						? wpfsFormSettings.l10n.application_errors
								.stripe_instantiation_error_message
						: wpfsFormSettings.l10n.application_errors
								.not_connected_to_stripe
					: wpfsFormSettings.l10n.generic_error,
				[ err.message ]
			);
			showErrorGlobalMessage(
				$form,
				wpfsFormSettings.l10n.application_errors
					.stripe_instantiation_error_title,
				message
			);
			console.log( 'Cannot instantiate Stripe: ' + err.message );
		}

		const reCAPTCHAWidgetIds = [];
		let googleReCAPTCHA = null;

		/*
		 * identifier functions
		 */
		function isDonationForm( $form ) {
			const formType = $form.data( FROM_TYPE_DOM );
			return (
				formType === FORM_TYPE_INLINE_DONATION ||
				formType === FORM_TYPE_CHECKOUT_DONATION
			);
		}

		function isSubscriptionForm( $form ) {
			const formType = $form.data( FROM_TYPE_DOM );
			return (
				FORM_TYPE_INLINE_SUBSCRIPTION === formType ||
				FORM_TYPE_CHECKOUT_SUBSCRIPTION === formType
			);
		}

		function isPaymentForm( $form ) {
			const formType = $form.data( FROM_TYPE_DOM );
			return (
				FORM_TYPE_INLINE_PAYMENT === formType ||
				FORM_TYPE_CHECKOUT_PAYMENT === formType
			);
		}
		function isPossiblePricingRecalculationForm( $form ) {
			return isPaymentForm( $form ) || isSubscriptionForm( $form );
		}

		/*
		 * DOM checkers
		 */
		function isBuyingAsABusiness( $form ) {
			return $( 'input[name="wpfs-buying-as-business"]', $form ).prop(
				'checked'
			);
		}
		function isNoCouponAppliedForm( $form ) {
			const coupon = WPFS.getCoupon( extractFormNameFromNode( $form ) );
			return coupon === null;
		}
		function isNoTaxForm( $form ) {
			let result = true;
			if ( isPossiblePricingRecalculationForm( $form ) ) {
				if (
					extractTaxRateTypeFromNode( $form ) !== TAX_RATE_TYPE_NO_TAX
				) {
					result = false;
				}
			}
			return result;
		}
		function isDynamicTaxesForm( $form ) {
			let result = false;
			if ( isPossiblePricingRecalculationForm( $form ) ) {
				if (
					extractTaxRateTypeFromNode( $form ) ===
					TAX_RATE_TYPE_DYNAMIC
				) {
					result = true;
				}
			}
			return result;
		}
		function isStripeTaxForm( $form ) {
			let result = false;
			if ( isPossiblePricingRecalculationForm( $form ) ) {
				if (
					extractTaxRateTypeFromNode( $form ) ===
					TAX_RATE_TYPE_STRIPE_TAX
				) {
					result = true;
				}
			}
			return result;
		}
		function isTaxIdCollectible( $form ) {
			return (
				$( 'input[name="wpfs-buying-as-business"]', $form ) &&
				$( 'input[name="wpfs-buying-as-business"]', $form ).length > 0
			);
		}
		function isSelectedProductDiscounted( $form ) {
			const priceId = findPriceIdForSelectedProduct( $form );
			const pricingData = WPFS.getPaymentDetails(
				extractFormNameFromNode( $form )
			);

			if ( pricingData ) {
				if ( pricingData.hasOwnProperty( priceId ) ) {
					const filteredItems = pricingData[ priceId ].filter(
						( item ) =>
							item.subType === PRICE_LINE_ITEM_SUBTYPE_DISCOUNT
					);
					if ( filteredItems && filteredItems.length > 0 ) {
						return true;
					}
				}
			}
			return false;
		}
		function isCouponFieldVisible( $form ) {
			let result = false;

			if ( $form.data( 'wpfs-show-coupon-field' ) === true ) {
				result = true;
			}

			return result;
		}

		/*
		 * Formatter functions
		 */

		function createCurrencyFormatter( $form ) {
			if ( $form ) {
				const decimalSeparator = $form.data( 'wpfs-decimal-separator' );
				const showCurrencySymbolInsteadOfCode = $form.data(
					'wpfs-show-currency-symbol-instead-of-code'
				);
				const showCurrencySignAtFirstPosition = $form.data(
					'wpfs-show-currency-sign-at-first-position'
				);
				const putWhitespaceBetweenCurrencyAndAmount = $form.data(
					'wpfs-put-whitespace-between-currency-and-amount'
				);

				if ( debugLog ) {
					logInfo(
						'createCurrencyFormatter',
						'form=' + $form.data( 'wpfs-form-id' )
					);
					logInfo(
						'createCurrencyFormatter',
						'decimalSeparator=' + JSON.stringify( decimalSeparator )
					);
					logInfo(
						'createCurrencyFormatter',
						'showCurrencySymbolInsteadOfCode=' +
							JSON.stringify( showCurrencySymbolInsteadOfCode )
					);
					logInfo(
						'createCurrencyFormatter',
						'showCurrencySignAtFirstPosition=' +
							JSON.stringify( showCurrencySignAtFirstPosition )
					);
					logInfo(
						'createCurrencyFormatter',
						'putWhitespaceBetweenCurrencyAndAmount=' +
							JSON.stringify(
								putWhitespaceBetweenCurrencyAndAmount
							)
					);
				}
				return WPFSCurrencyFormatter(
					decimalSeparator,
					showCurrencySymbolInsteadOfCode,
					showCurrencySignAtFirstPosition,
					putWhitespaceBetweenCurrencyAndAmount
				);
			}
			logWarn( 'createCurrencyFormatter', '$form is null' );
			return null;
		}

		/**
		 *
		 * @param  currency
		 * @param  amount
		 * @param  coupon
		 * @return {{amountCurrency: *, amount: *, couponCurrency: (OPTIONS.currency|{importance, type, default}|*|Array), discount: number, discountType: *, total: number, error: *}}
		 */
		function applyCoupon( currency, amount, coupon ) {
			// console.log('applyCoupon(): CALLED, currency=' + currency + ', amount=' + amount + ', coupon=' + JSON.stringify(coupon));
			let discount = 0;
			let discountType = null;
			let error = null;
			if ( coupon != null ) {
				//noinspection JSUnresolvedVariable
				if (
					coupon.hasOwnProperty( 'percent_off' ) &&
					coupon.percent_off != null
				) {
					discountType = 'percent_off';
					//noinspection JSUnresolvedVariable
					const percentOff = parseInt( coupon.percent_off ) / 100;
					discount = Math.round( amount * percentOff );
				} else {
					//noinspection JSUnresolvedVariable
					if (
						coupon.hasOwnProperty( 'amount_off' ) &&
						coupon.amount_off != null
					) {
						discountType = 'amount_off';
						if ( coupon.hasOwnProperty( 'currency' ) ) {
							if ( coupon.currency == currency ) {
								//noinspection JSUnresolvedVariable
								discount = parseInt( coupon.amount_off );
							} else {
								error = 'currency mismatch';
							}
						} else {
							error = 'currency mismatch';
						}
					} else {
						error = 'invalid coupon';
					}
				}
			}
			const amountAsInteger = parseInt( amount );
			const total = amountAsInteger - discount;

			const result = {
				amountCurrency: currency,
				amount: amountAsInteger,
				couponCurrency:
					coupon != null && coupon.hasOwnProperty( 'currency' )
						? coupon.currency
						: null,
				discount,
				discountType,
				discountPercentOff:
					coupon != null && coupon.hasOwnProperty( 'percent_off' )
						? coupon.percent_off
						: null,
				total,
				error,
			};

			// console.log('applyCoupon(): result=' + JSON.stringify(result));

			return result;
		}

		function resetCaptcha( $form ) {
			if ( $form ) {
				const formHash = $form.data( 'wpfs-form-hash' );
				if ( formHash ) {
					if ( googleReCAPTCHA != null ) {
						if ( reCAPTCHAWidgetIds[ formHash ] !== 'undefined' ) {
							googleReCAPTCHA.reset(
								reCAPTCHAWidgetIds[ formHash ]
							);
						}
					}
				}
			}
		}

		function getParentForm( element ) {
			return $( element ).parents( 'form:first' );
		}

		function isInViewport( $anElement ) {
			const $window = $( window );

			//noinspection JSValidateTypes
			const viewPortTop = $window.scrollTop();
			const viewPortBottom = viewPortTop + $window.height();

			const elementTop = $anElement.offset().top;
			const elementBottom = elementTop + $anElement.outerHeight();

			if ( debugLog ) {
				console.log(
					'isInViewport(): elementBottom=' +
						elementBottom +
						', viewPortBottom=' +
						viewPortBottom +
						', elementTop=' +
						elementTop +
						', viewPortTop=' +
						viewPortTop
				);
			}

			return elementBottom <= viewPortBottom && elementTop >= viewPortTop;
		}

		function getGlobalMessageContainerTitle( $messageContainer, title ) {
			let $messageContainerTitle = $(
				'.wpfs-form-message-title',
				$messageContainer
			);
			if ( 0 == $messageContainerTitle.length ) {
				$( '<div>', { class: 'wpfs-form-message-title' } ).prependTo(
					$messageContainer
				);
				$messageContainerTitle = $(
					'.wpfs-form-message-title',
					$messageContainer
				);
			}
			$messageContainerTitle.html( title );
			return $messageContainerTitle;
		}

		function getGlobalMessageContainer( $form, message ) {
			let $messageContainer = $( '.wpfs-form-message', $form );
			if ( 0 == $messageContainer.length ) {
				$( '<div>', { class: 'wpfs-form-message' } ).prependTo( $form );
				$messageContainer = $( '.wpfs-form-message', $form );
			}
			$messageContainer.html( message );
			return $messageContainer;
		}

		function scrollToElement( $anElement, fade ) {
			if (
				$anElement &&
				$anElement.offset() &&
				$anElement.offset().top
			) {
				if ( ! isInViewport( $anElement ) ) {
					$( 'html, body' ).animate(
						{
							scrollTop: $anElement.offset().top - 100,
						},
						1000
					);
				}
			}
			if ( $anElement && fade ) {
				$anElement.fadeIn( 500 ).fadeOut( 500 ).fadeIn( 500 );
			}
		}

		function clearGlobalMessage( $form ) {
			const $messageContainer = getGlobalMessageContainer( $form, '' );
			$messageContainer.remove();
		}

		function __showGlobalMessage( $form, messageTitle, message ) {
			const $globalMessageContainer = getGlobalMessageContainer(
				$form,
				message
			);
			getGlobalMessageContainerTitle(
				$globalMessageContainer,
				messageTitle
			);
			return $globalMessageContainer;
		}

		function showSuccessGlobalMessage( $form, messageTitle, message ) {
			const $globalMessageContainer = __showGlobalMessage(
				$form,
				messageTitle,
				message
			);
			$globalMessageContainer.addClass( 'wpfs-form-message--correct' );
			scrollToElement( $globalMessageContainer, false );
		}

		function showErrorGlobalMessage( $form, messageTitle, message ) {
			const $globalMessageContainer = __showGlobalMessage(
				$form,
				messageTitle,
				message
			);
			$globalMessageContainer.addClass( 'wpfs-form-message--incorrect' );
			scrollToElement( $globalMessageContainer, false );
		}

		function clearFieldErrors( $form ) {
			$( '.wpfs-form-error-message', $form ).remove();
			$( '.wpfs-form-control', $form ).removeClass(
				'wpfs-form-control--error'
			);
			$( '.wpfs-input-group', $form ).removeClass(
				'wpfs-input-group--error'
			);
			$( '.wpfs-form-control--error', $form ).removeClass(
				'wpfs-form-control--error'
			);
			$( '.wpfs-form-check-input--error', $form ).removeClass(
				'wpfs-form-check-input--error'
			);
		}

		function clearFieldError( $form, fieldName, fieldId ) {
			const formType = $form.data( 'wpfs-form-type' );
			const fieldDescriptor = getFieldDescriptor( formType, fieldName );
			if ( debugLog ) {
				logInfo(
					'clearFieldError',
					'fieldName=' + fieldName + ', fieldId=' + fieldId
				);
				logInfo( 'clearFieldError', JSON.stringify( fieldDescriptor ) );
			}
			if ( fieldDescriptor != null ) {
				// tnagy read field descriptor
				const fieldType = fieldDescriptor.type;
				const fieldClass = fieldDescriptor.class;
				const fieldSelector = fieldDescriptor.selector;
				const fieldErrorClass = fieldDescriptor.errorClass;
				let fieldErrorSelector = fieldDescriptor.errorSelector;

				// tnagy initialize field
				let theFieldSelector;
				if ( fieldId != null ) {
					theFieldSelector = '#' + fieldId;
				} else {
					theFieldSelector = fieldSelector;
				}
				const $field = $( theFieldSelector, $form );

				// tnagy remove error class, remove error message
				const errorMessageFieldSelector =
					'.' + ERROR_MESSAGE_FIELD_CLASS;
				if ( 'input' === fieldType ) {
					if ( fieldErrorSelector != null ) {
						if (
							fieldErrorSelector.indexOf(
								FIELD_DESCRIPTOR_MACRO_FIELD_ID
							) !== -1
						) {
							fieldErrorSelector = fieldErrorSelector.replace(
								/\{fieldId}/g,
								fieldId
							);
						}
						$field
							.closest( fieldErrorSelector )
							.removeClass( fieldErrorClass );
					}
					$field.closest( errorMessageFieldSelector ).remove();
				} else if ( 'input-group' === fieldType ) {
					if ( fieldErrorSelector != null ) {
						if (
							fieldErrorSelector.indexOf(
								FIELD_DESCRIPTOR_MACRO_FIELD_ID
							) !== -1
						) {
							fieldErrorSelector = fieldErrorSelector.replace(
								/\{fieldId}/g,
								fieldId
							);
						}
						$field
							.closest( fieldErrorSelector )
							.removeClass( fieldErrorClass );
					}
					$field
						.closest( fieldErrorSelector )
						.siblings( errorMessageFieldSelector )
						.remove();
				} else if ( 'input-custom' === fieldType ) {
					if ( fieldErrorSelector != null ) {
						$field
							.closest( fieldErrorSelector )
							.removeClass( fieldErrorClass );
					}
					$field.closest( errorMessageFieldSelector ).remove();
				} else if ( 'select-menu' === fieldType ) {
					if ( fieldErrorSelector != null ) {
						if (
							fieldErrorSelector.indexOf(
								FIELD_DESCRIPTOR_MACRO_FIELD_ID
							) !== -1
						) {
							fieldErrorSelector = fieldErrorSelector.replace(
								/\{fieldId}/g,
								fieldId
							);
						}
						$field
							.closest( '.' + fieldClass )
							.removeClass( fieldErrorClass );
						$( fieldErrorSelector ).removeClass( fieldErrorClass );
					}
					$field.closest( errorMessageFieldSelector ).remove();
				} else if ( 'checkbox' === fieldType ) {
					if ( fieldErrorSelector != null ) {
						$field
							.closest( fieldErrorSelector )
							.removeClass( fieldErrorClass );
					}
					$field.closest( errorMessageFieldSelector ).remove();
				} else if ( 'card' === fieldType ) {
					if ( fieldErrorSelector != null ) {
						const $cardContainer = $( '.' + fieldClass, $form );
						$cardContainer
							.closest( fieldErrorSelector )
							.removeClass( fieldErrorClass );
					}
					$field.closest( errorMessageFieldSelector ).remove();
				}
			} else {
				logInfo( 'showFieldError', 'FieldDescription not found!' );
			}
		}

		function getFieldDescriptor( formType, fieldName ) {
			let fieldDescriptor = null;
			if (
				FORM_TYPE_INLINE_PAYMENT === formType &&
				wpfsFormSettings.formFields.hasOwnProperty( 'inlinePayment' )
			) {
				const inlinePaymentFormFields =
					wpfsFormSettings.formFields.inlinePayment;
				if ( inlinePaymentFormFields.hasOwnProperty( fieldName ) ) {
					fieldDescriptor = inlinePaymentFormFields[ fieldName ];
				}
			} else if (
				FORM_TYPE_INLINE_SUBSCRIPTION === formType &&
				wpfsFormSettings.formFields.hasOwnProperty(
					'inlineSubscription'
				)
			) {
				const inlineSubscriptionFormFields =
					wpfsFormSettings.formFields.inlineSubscription;
				if (
					inlineSubscriptionFormFields.hasOwnProperty( fieldName )
				) {
					fieldDescriptor = inlineSubscriptionFormFields[ fieldName ];
				}
			} else if (
				FORM_TYPE_CHECKOUT_PAYMENT === formType &&
				wpfsFormSettings.formFields.hasOwnProperty( 'checkoutPayment' )
			) {
				const popupPaymentFormFields =
					wpfsFormSettings.formFields.checkoutPayment;
				if ( popupPaymentFormFields.hasOwnProperty( fieldName ) ) {
					fieldDescriptor = popupPaymentFormFields[ fieldName ];
				}
			} else if (
				FORM_TYPE_CHECKOUT_SUBSCRIPTION === formType &&
				wpfsFormSettings.formFields.hasOwnProperty(
					'checkoutSubscription'
				)
			) {
				const popupSubscriptionFormFields =
					wpfsFormSettings.formFields.checkoutSubscription;
				if ( popupSubscriptionFormFields.hasOwnProperty( fieldName ) ) {
					fieldDescriptor = popupSubscriptionFormFields[ fieldName ];
				}
			} else if (
				FORM_TYPE_INLINE_SAVE_CARD === formType &&
				wpfsFormSettings.formFields.hasOwnProperty( 'inlineSaveCard' )
			) {
				const inlineCardCaptureFormFields =
					wpfsFormSettings.formFields.inlineSaveCard;
				if ( inlineCardCaptureFormFields.hasOwnProperty( fieldName ) ) {
					fieldDescriptor = inlineCardCaptureFormFields[ fieldName ];
				}
			} else if (
				FORM_TYPE_CHECKOUT_SAVE_CARD === formType &&
				wpfsFormSettings.formFields.hasOwnProperty( 'checkoutSaveCard' )
			) {
				const popupCardCaptureFormFields =
					wpfsFormSettings.formFields.checkoutSaveCard;
				if ( popupCardCaptureFormFields.hasOwnProperty( fieldName ) ) {
					fieldDescriptor = popupCardCaptureFormFields[ fieldName ];
				}
			} else if (
				FORM_TYPE_INLINE_DONATION === formType &&
				wpfsFormSettings.formFields.hasOwnProperty( 'inlineDonation' )
			) {
				const inlineDonationFormFields =
					wpfsFormSettings.formFields.inlineDonation;
				if ( inlineDonationFormFields.hasOwnProperty( fieldName ) ) {
					fieldDescriptor = inlineDonationFormFields[ fieldName ];
				}
			} else if (
				FORM_TYPE_CHECKOUT_DONATION === formType &&
				wpfsFormSettings.formFields.hasOwnProperty( 'checkoutDonation' )
			) {
				const popupDonationFormFields =
					wpfsFormSettings.formFields.checkoutDonation;
				if ( popupDonationFormFields.hasOwnProperty( fieldName ) ) {
					fieldDescriptor = popupDonationFormFields[ fieldName ];
				}
			}
			return fieldDescriptor;
		}

		function showFormError(
			$form,
			fieldName,
			fieldId,
			errorTitle,
			errorMessage,
			scrollTo
		) {
			const formType = $form.data( 'wpfs-form-type' );
			const fieldDescriptor = getFieldDescriptor( formType, fieldName );
			if ( fieldDescriptor != null ) {
				const fieldIsHidden = fieldDescriptor.hidden;
				if ( true == fieldIsHidden ) {
					showErrorGlobalMessage( $form, errorTitle, errorMessage );
				} else {
					showFieldError(
						$form,
						fieldName,
						fieldId,
						errorMessage,
						scrollTo
					);
				}
				if ( fieldName.startsWith( 'wpfs-billing' ) ) {
					$( '.wpfs-billing-address-switch', $form ).click();
				}
				if ( fieldName.startsWith( 'wpfs-shipping' ) ) {
					$( '.wpfs-shipping-address-switch', $form ).click();
				}
			}
		}

		function showFieldError(
			$form,
			fieldName,
			fieldId,
			fieldErrorMessage,
			scrollTo
		) {
			const formType = $form.data( 'wpfs-form-type' );
			const fieldDescriptor = getFieldDescriptor( formType, fieldName );
			if ( debugLog ) {
				logInfo(
					'showFieldError',
					'fieldName=' +
						fieldName +
						', fieldId=' +
						fieldId +
						', fieldErrorMessage=' +
						fieldErrorMessage
				);
				logInfo( 'showFieldError', JSON.stringify( fieldDescriptor ) );
				logInfo( 'showFieldError', fieldErrorMessage );
			}
			if ( fieldDescriptor != null ) {
				// tnagy read field descriptor
				const fieldType = fieldDescriptor.type;
				const fieldClass = fieldDescriptor.class;
				const fieldSelector = fieldDescriptor.selector;
				const fieldErrorClass = fieldDescriptor.errorClass;
				let fieldErrorSelector = fieldDescriptor.errorSelector;

				// tnagy initialize field
				let theFieldSelector;
				if ( fieldId != null ) {
					theFieldSelector = '#' + fieldId;
				} else {
					theFieldSelector = fieldSelector;
				}
				const $field = $( theFieldSelector, $form );

				// tnagy create error message
				const $fieldError = $( '<div>', {
					class: ERROR_MESSAGE_FIELD_CLASS,
					'data-wpfs-field-error-for': fieldId,
				} ).html( fieldErrorMessage );

				// tnagy add error class, insert error message
				if ( 'input' === fieldType ) {
					if ( fieldErrorSelector != null ) {
						if (
							fieldErrorSelector.indexOf(
								FIELD_DESCRIPTOR_MACRO_FIELD_ID
							) !== -1
						) {
							fieldErrorSelector = fieldErrorSelector.replace(
								/\{fieldId}/g,
								fieldId
							);
						}
						$field
							.closest( fieldErrorSelector )
							.addClass( fieldErrorClass );
					}
					$fieldError.insertAfter( $field );
				} else if ( 'input-group' === fieldType ) {
					if ( fieldErrorSelector != null ) {
						if (
							fieldErrorSelector.indexOf(
								FIELD_DESCRIPTOR_MACRO_FIELD_ID
							) !== -1
						) {
							fieldErrorSelector = fieldErrorSelector.replace(
								/\{fieldId}/g,
								fieldId
							);
						}
						$field
							.closest( fieldErrorSelector )
							.addClass( fieldErrorClass );
					}
					$fieldError.insertAfter(
						$field.closest( fieldErrorSelector )
					);
				} else if ( 'input-custom' === fieldType ) {
					if ( fieldErrorSelector != null ) {
						$field
							.closest( fieldErrorSelector )
							.addClass( fieldErrorClass );
					}
					$fieldError.insertAfter( $field );
				} else if ( 'dropdown' === fieldType ) {
					if ( fieldErrorSelector != null ) {
						if (
							fieldErrorSelector.indexOf(
								FIELD_DESCRIPTOR_MACRO_FIELD_ID
							) !== -1
						) {
							fieldErrorSelector = fieldErrorSelector.replace(
								/\{fieldId}/g,
								fieldId
							);
						}
						$field
							.closest( '.' + fieldClass )
							.addClass( fieldErrorClass );
						$( fieldErrorSelector ).addClass( fieldErrorClass );
					}
					$fieldError.appendTo( $field.parent() );
				} else if ( 'checkbox' === fieldType ) {
					if ( fieldErrorSelector != null ) {
						$field
							.closest( fieldErrorSelector )
							.addClass( fieldErrorClass );
					}
					$fieldError.appendTo( $field.parent() );
				} else if ( 'card' === fieldType ) {
					if ( fieldErrorSelector != null ) {
						const $cardContainer = $( '.' + fieldClass, $form );
						$cardContainer
							.closest( fieldErrorSelector )
							.addClass( fieldErrorClass );
						$fieldError.insertAfter( $cardContainer );
					}
				} else if ( 'captcha' === fieldType ) {
					if ( fieldErrorSelector != null ) {
						const $captchaContainer = $( '.' + fieldClass, $form );
						$captchaContainer
							.closest( fieldErrorSelector )
							.addClass( fieldErrorClass );
						$fieldError.insertAfter( $captchaContainer );
					}
				}

				if ( typeof scrollTo !== 'undefined' ) {
					if ( scrollTo ) {
						scrollToElement( $field, false );
					}
				}
			} else {
				logInfo( 'showFieldError', 'FieldDescription not found!' );
			}
		}

		function processValidationErrors( $form, data ) {
			const formId = $form.data( 'wpfs-form-id' );
			const formHash = $form.data( 'wpfs-form-hash' );
			let hasErrors = false;
			if ( data && data.bindingResult ) {
				if (
					data.bindingResult.fieldErrors &&
					data.bindingResult.fieldErrors.errors
				) {
					const fieldErrors = data.bindingResult.fieldErrors.errors;
					for ( const index in fieldErrors ) {
						const fieldError = fieldErrors[ index ];
						const fieldId = fieldError.id;
						const fieldName = fieldError.name;
						const fieldErrorMessage = fieldError.message;
						showFormError(
							$form,
							fieldName,
							fieldId,
							data.bindingResult.fieldErrors.title,
							fieldErrorMessage
						);
						if ( ! hasErrors ) {
							hasErrors = true;
						}
					}
					const firstErrorFieldId = $(
						'.wpfs-form-error-message',
						$form
					)
						.first()
						.data( 'wpfs-field-error-for' );
					if ( firstErrorFieldId ) {
						scrollToElement(
							$( '#' + firstErrorFieldId, $form ),
							false
						);
					}
				}
				if ( data.bindingResult.globalErrors ) {
					let globalErrorMessages = '';
					for (
						let i = 0;
						i < data.bindingResult.globalErrors.errors.length;
						i++
					) {
						globalErrorMessages +=
							data.bindingResult.globalErrors.errors[ i ] +
							'<br/>';
					}
					if ( '' !== globalErrorMessages ) {
						showErrorGlobalMessage(
							$form,
							data.bindingResult.globalErrors.title,
							globalErrorMessages
						);
						if ( ! hasErrors ) {
							hasErrors = true;
						}
					}
				}
			} else {
				showErrorGlobalMessage(
					$form,
					data.messageTitle,
					data.message
				);
				logResponseException( 'WPFS form=' + formId, data );
				hasErrors = true;
			}
			if ( hasErrors ) {
				resetCaptcha( $form );
			}
		}

		function clearAndShowAndEnableAndFocusCustomAmount( $form ) {
			$( 'div[data-wpfs-amount-row=custom-amount]', $form ).show();
			$( 'input[name=wpfs-custom-amount-unique]', $form )
				.val( '' )
				.prop( 'disabled', false )
				.prop( 'readonly', false )
				.focus();
		}

		function clearAndHideAndDisableCustomAmount( $form ) {
			$( 'div[data-wpfs-amount-row="custom-amount"]', $form ).hide();
			$( 'input[name="wpfs-custom-amount-unique"]', $form )
				.val( '' )
				.prop( 'disabled', true );

			WPFS.setPaymentDetailsForPrice(
				extractFormNameFromNode( $form ),
				PRICE_ID_CUSTOM_AMOUNT,
				createCustomAmountPricingData( $form, 0 )
			);
		}

		function updateButtonCaption(
			$form,
			buttonTitle,
			currencyCode,
			currencySymbol,
			amount,
			zeroDecimalSupport
		) {
			if ( debugLog ) {
				console.log(
					'updateButtonCaption(): params=' +
						JSON.stringify( [
							currencyCode,
							currencySymbol,
							amount,
							zeroDecimalSupport,
						] )
				);
				const formId = $form.data( 'wpfs-form-id' );
				console.log( 'updateButtonCaption(): formId=' + formId );
			}

			const formatter = createCurrencyFormatter( $form );
			let captionPattern;
			let caption;
			const amountMacroRegExp = new RegExp(
				MACRO_SUBMIT_BUTTON_CAPTION_AMOUNT,
				'g'
			);
			if ( currencySymbol == null || amount == null ) {
				caption = buttonTitle;
				if (
					caption.indexOf( MACRO_SUBMIT_BUTTON_CAPTION_AMOUNT ) !== -1
				) {
					caption = caption.replace( amountMacroRegExp, '' ).trim();
				}
			} else {
				const amountPart = formatter.format(
					amount,
					currencyCode,
					currencySymbol,
					zeroDecimalSupport
				);
				if (
					buttonTitle.indexOf(
						MACRO_SUBMIT_BUTTON_CAPTION_AMOUNT
					) !== -1
				) {
					captionPattern = buttonTitle
						.replace( amountMacroRegExp, '%s' )
						.trim();
					const captionPatternParams = [];

					captionPatternParams.push( amountPart );
					caption = vsprintf( captionPattern, captionPatternParams );
				} else {
					caption = buttonTitle;
				}
			}
			$form.find( 'button[type=submit]' ).html( caption );
		}

		function disableFormButtons( $form ) {
			$form.find( 'button' ).prop( 'disabled', true );
		}

		function enableFormButtons( $form ) {
			$form.find( 'button' ).prop( 'disabled', false );
		}

		function disableFormInputsSelectsButtons( $form ) {
			if ( debugLog ) {
				console.log( 'disableFormInputsSelectsButtons(): CALLED' );
			}
			$form
				.find(
					'input:not(.wpfs-custom-amount), select:not([data-wpfs-select]), button'
				)
				.prop( 'disabled', true );
		}

		function enableFormInputsSelectsButtons( $form ) {
			if ( debugLog ) {
				console.log( 'enableFormInputsSelectsButtons(): CALLED' );
			}
			$form
				.find( 'input, select:not([data-wpfs-select]), button' )
				.prop( 'disabled', false );
		}

		function resetTextFields( $form ) {
			$form
				.find(
					'input[type=text], input[type=password], input[type=email]'
				)
				.val( '' );
		}

		function resetStepperFields( $form ) {
			$form.find( 'input[data-toggle="stepper"]' ).each( function () {
				let defaultValue = 1;
				if (
					typeof $( this ).data( 'default-value' ) !== 'undefined'
				) {
					defaultValue = $( this ).data( 'default-value' );
				}
				$( this ).val( defaultValue );
			} );
		}

		function resetSelectFields( $form ) {
			$form.find( 'select' ).prop( 'selectedIndex', 0 ).change();
			$form
				.find( '[data-toggle="selectmenu"]' )
				.wpfsSelectmenu( 'refresh' );
		}

		function resetCheckboxesAndRadioButtons( $form ) {
			$form
				.find( 'input[type=radio], input[type=checkbox]' )
				.prop( 'checked', false );
		}

		function resetCardField( card ) {
			if ( card != null ) {
				card.clear();
			}
		}

		function resetCouponField( $form, formId ) {
			const coupon = WPFS.getCoupon( formId );
			if ( coupon != null ) {
				WPFS.removeCoupon( formId );
				hideRedeemedCouponRow( $form );
				hideWrongRedeemedCouponRow( $form );
				showCouponToRedeemRow( $form );
			}
		}

		function resetBillingAndShippingAddressSelector( $form ) {
			$( '.wpfs-same-billing-and-shipping-address', $form )
				.prop( 'checked', true )
				.change();
		}

		function selectFirstCustomAmount( $form ) {
			$( '.wpfs-custom-amount', $form ).first().click();
		}

		function selectFirstPlanFromDropdown( $form ) {
			const $planSelectElements = $form.find(
				'select.wpfs-subscription-plan-select'
			);
			$planSelectElements.each( function () {
				if ( $( 'option:selected', $( this ) ).length ) {
					$( this ).val( $( 'option:selected', $( this ) ).val() );
				} else {
					$( this ).val( $( 'option:first', $( this ) ).val() );
				}
			} );
		}

		function selectFirstPlanFromButtonGroup( $form ) {
			if (
				$(
					'input:radio[name="wpfs-plan"]:not(:disabled):checked',
					$form
				).length === 0
			) {
				$(
					'input:radio[name="wpfs-plan"]:not(:disabled):first',
					$form
				).prop( 'checked', true );
			}
		}

		function selectFirstDonationFrequency( $form ) {
			if (
				$(
					'input:radio[name="wpfs-donation-frequency"]:not(:disabled):checked',
					$form
				).length === 0
			) {
				$(
					'input:radio[name="wpfs-donation-frequency"]:not(:disabled):first',
					$form
				).prop( 'checked', true );
			}
		}

		function selectFirstSubscriptionPlan( $form ) {
			selectFirstPlanFromDropdown( $form );
			selectFirstPlanFromButtonGroup( $form );

			handlePlanChange( $form );
		}

		function resetFormFields( $form, card ) {
			const formId = $form.data( 'wpfs-form-id' );
			const formHash = $form.data( 'wpfs-form-hash' );

			resetTextFields( $form );
			resetStepperFields( $form );
			resetSelectFields( $form );
			resetCheckboxesAndRadioButtons( $form );
			resetCardField( card );
			resetCouponField( $form, formId );
			resetCaptcha( $form );
			resetBillingAndShippingAddressSelector( $form );
			removeHiddenFormFields( $form );

			selectFirstCustomAmount( $form );
			selectFirstSubscriptionPlan( $form );
			selectFirstDonationFrequency( $form );
		}

		function removeCustomAmountIndexInput( $form ) {
			$form.find( 'input[name="wpfs-custom-amount-index"]' ).remove();
		}

		function removeHiddenFormFields( $form ) {
			removePaymentMethodIdInput( $form );
			removePaymentIntentIdInput( $form );
			removeSetupIntentIdInput( $form );
			removeSubscriptionIdInput( $form );
			removeCustomAmountIndexInput( $form );
			removeWPFSNonceInput( $form );
		}

		function showLoadingAnimation( $form ) {
			$form
				.find( 'button[type=submit]' )
				.addClass( 'wpfs-btn-primary--loader' );
		}

		function hideLoadingAnimation( $form ) {
			$form
				.find( 'button[type=submit]' )
				.removeClass( 'wpfs-btn-primary--loader' );
		}

		function clearPaymentDetails( $form ) {
			const rowTypes = [
				PAYMENT_DETAIL_ROW_SETUP_FEE,
				PAYMENT_DETAIL_ROW_PRODUCT,
				PAYMENT_DETAIL_ROW_DISCOUNT,
				PAYMENT_DETAIL_ROW_TAX_0,
				PAYMENT_DETAIL_ROW_TAX_1,
				PAYMENT_DETAIL_ROW_TOTAL,
			];

			rowTypes.forEach( ( rowType ) => {
				$(
					'tr[data-wpfs-summary-row="' + rowType + '"]',
					$form
				).hide();
			} );
			$( '.wpfs-summary-description', $form ).hide();
		}

		function addPaymentMethodIdInput( $form, result ) {
			if (
				typeof result !== 'undefined' &&
				result.hasOwnProperty( 'setupIntent' ) &&
				result.setupIntent.hasOwnProperty( 'payment_method' )
			) {
				$( '<input>' )
					.attr( {
						type: 'hidden',
						name: 'wpfs-stripe-payment-method-id',
						value: result.setupIntent.payment_method,
					} )
					.appendTo( $form );
			}
		}

		function addPaymentIntentIdInput( $form, result ) {
			if (
				typeof result !== 'undefined' &&
				result.hasOwnProperty( 'paymentIntent' ) &&
				result.paymentIntent.hasOwnProperty( 'id' )
			) {
				$( '<input>' )
					.attr( {
						type: 'hidden',
						name: 'wpfs-stripe-payment-intent-id',
						value: result.paymentIntent.id,
					} )
					.appendTo( $form );
			}
		}

		function addSetupIntentIdInput( $form, result ) {
			if (
				typeof result !== 'undefined' &&
				result.hasOwnProperty( 'setupIntent' ) &&
				result.setupIntent.hasOwnProperty( 'id' )
			) {
				$( '<input>' )
					.attr( {
						type: 'hidden',
						name: 'wpfs-stripe-setup-intent-id',
						value: result.setupIntent.id,
					} )
					.appendTo( $form );
			}
		}

		function addWPFSNonceInput( $form, data ) {
			if (
				typeof data !== 'undefined' &&
				data.hasOwnProperty( 'nonce' )
			) {
				$( '<input>' )
					.attr( {
						type: 'hidden',
						name: 'wpfs-nonce',
						value: data.nonce,
					} )
					.appendTo( $form );
			}
		}

		function removePaymentMethodIdInput( $form ) {
			$( 'input[name="wpfs-stripe-payment-method-id"]', $form ).remove();
		}

		function removeWPFSNonceInput( $form ) {
			$( 'input[name="wpfs-nonce"]', $form ).remove();
		}

		function removePaymentIntentIdInput( $form ) {
			$( 'input[name="wpfs-stripe-payment-intent-id"]', $form ).remove();
		}

		function removeSetupIntentIdInput( $form ) {
			$( 'input[name="wpfs-stripe-setup-intent-id"]', $form ).remove();
		}

		function removeSubscriptionIdInput( $form ) {
			$( 'input[name="wpfs-stripe-subscription-id"]', $form ).remove();
		}

		function findListOfAmountsElement( $form ) {
			let $element = null;
			const amountSelectorStyle = $form.data( 'wpfs-selector-style' );
			if ( SELECTOR_STYLE_RADIO_BUTTONS === amountSelectorStyle ) {
				$element = $form.find( 'input[name="wpfs-custom-amount"]' );
			} else if ( SELECTOR_STYLE_BUTTON_GROUP === amountSelectorStyle ) {
				$element = $form.find( 'input[name="wpfs-custom-amount"]' );
			} else if ( SELECTOR_STYLE_DROPDOWN === amountSelectorStyle ) {
				$element = $form.find( 'select[name="wpfs-custom-amount"]' );
			}
			return $element;
		}

		function findCustomAmountElement( $form ) {
			return $form.find( 'input[name="wpfs-custom-amount-unique"]' );
		}

		function findSelectedAmountFromListOfAmounts( $form ) {
			let amount = null;
			const amountSelectorStyle = $form.data( 'wpfs-selector-style' );
			if ( SELECTOR_STYLE_RADIO_BUTTONS === amountSelectorStyle ) {
				amount = $form.find(
					'input[name="wpfs-custom-amount"]:checked'
				);
			} else if ( SELECTOR_STYLE_BUTTON_GROUP === amountSelectorStyle ) {
				amount = $form.find(
					'input[name="wpfs-custom-amount"]:checked'
				);
			} else if ( SELECTOR_STYLE_DROPDOWN === amountSelectorStyle ) {
				amount = $form.find(
					'select[name="wpfs-custom-amount"] option:selected'
				);
			}

			// This is for finding the custom hidden element if there are no preset amounts
			if ( amount == null || amount.length === 0 ) {
				amount = $form.find( 'input[name="wpfs-custom-amount"]' );
			}

			return amount;
		}

		function addCustomAmountIndexInput( $form ) {
			const $selectedAmount =
				findSelectedAmountFromListOfAmounts( $form );
			if ( $selectedAmount !== null && $selectedAmount.length > 0 ) {
				const amountIndex = $selectedAmount.data( 'wpfs-amount-index' );
				if ( typeof amountIndex !== 'undefined' ) {
					$( '<input>' )
						.attr( {
							type: 'hidden',
							name: 'wpfs-custom-amount-index',
							value: encodeURIComponent( amountIndex ),
						} )
						.appendTo( $form );
				}
			}
		}

		function addCurrentURL( $form ) {
			$( '<input>' )
				.attr( {
					type: 'hidden',
					name: 'wpfs-referrer',
					value: encodeURI( window.location.href ),
				} )
				.appendTo( $form );
		}

		function setPageParametersField( $form ) {
			const params = getQueryStringIntoArray();
			const paramsJson = JSON.stringify( params );
			$( 'input[name="wpfs-form-get-parameters"]', $form ).val(
				encodeURIComponent( paramsJson )
			);
		}

		class WpfsAbstractFormSerializer {
			constructor( $form ) {
				this.$form = $form;
			}
		}

		class WpfsDefaultFormSerializer extends WpfsAbstractFormSerializer {
			constructor( $form ) {
				super( $form );
			}

			serialize() {
				return this.$form.serialize();
			}
		}

		class WpfsPaymentFormSerializer extends WpfsAbstractFormSerializer {
			constructor( $form ) {
				super( $form );
			}

			serialize() {
				if ( isSelectedProductDiscounted( this.$form ) ) {
					return this.$form.serialize();
				}

				return $(
					":input[name!='wpfs-coupon']",
					this.$form
				).serialize();
			}
		}

		function createFormSerializer( $form ) {
			const formType = $form.data( FROM_TYPE_DOM );

			switch ( formType ) {
				case FORM_TYPE_INLINE_PAYMENT:
				case FORM_TYPE_INLINE_SUBSCRIPTION:
					return new WpfsPaymentFormSerializer( $form );

				case FORM_TYPE_INLINE_DONATION:
				case FORM_TYPE_INLINE_SAVE_CARD:
				case FORM_TYPE_CHECKOUT_PAYMENT:
				case FORM_TYPE_CHECKOUT_SUBSCRIPTION:
				case FORM_TYPE_CHECKOUT_DONATION:
				case FORM_TYPE_CHECKOUT_SAVE_CARD:
					return new WpfsDefaultFormSerializer( $form );

				default:
					throw new Exception(
						'createFormSerializer() - Unknown form type: ' +
							formType
					);
			}
		}

		function getSetupIntentClientSecret( $form ) {
			return new Promise( ( resolve, reject ) => {
				if ( debugLog ) {
					logInfo( 'getSetupIntentClientSecret', 'CALLED' );
				}

				let data = $form.serialize();
				data = JSON.parse(
					'{"' +
						data.replace( /&/g, '","' ).replace( /=/g, '":"' ) +
						'"}',
					function ( key, value ) {
						return key === '' ? value : decodeURIComponent( value );
					}
				);
				data.action = 'wp_get_Setup_Intent_Client_Secret';

				$.ajax( {
					type: 'POST',
					url: wpfsFormSettings.ajaxUrl,
					data,
					cache: false,
					dataType: 'json',
					success( data ) {
						if ( debugLog ) {
							logInfo(
								'getSetupIntentClientSecret',
								'SUCCESS response=' + JSON.stringify( data )
							);
						}
						resolve( data );
					},
					error( jqXHR, textStatus, errorThrown ) {
						logError(
							'submitPaymentData',
							jqXHR,
							textStatus,
							errorThrown
						);

						if ( jqXHR?.responseJSON?.message ) {
							showErrorGlobalMessage(
								$form,
								wpfsFormSettings.l10n.validation_errors
									.internal_error_title,
								jqXHR.responseJSON.message
							);
						} else {
							showErrorGlobalMessage(
								$form,
								wpfsFormSettings.l10n.validation_errors
									.internal_error_title,
								wpfsFormSettings.l10n.validation_errors
									.internal_error
							);
						}

						reject( errorThrown );
					},
				} );
			} );
		}

		function submitPaymentData( $form, card ) {
			if ( debugLog ) {
				logInfo( 'submitPaymentData', 'CALLED' );
			}

			clearGlobalMessage( $form );
			clearFieldErrors( $form );

			$.ajax( {
				type: 'POST',
				url: wpfsFormSettings.ajaxUrl,
				data: createFormSerializer( $form ).serialize(),
				cache: false,
				dataType: 'json',
				success( data ) {
					if ( debugLog ) {
						logInfo(
							'submitPaymentData',
							'SUCCESS response=' + JSON.stringify( data )
						);
					}

					if ( data.nonce ) {
						removeWPFSNonceInput( $form );
						addWPFSNonceInput( $form, data );
					}

					if ( data.success ) {
						if ( data.redirect ) {
							window.location = data.redirectURL;
						} else {
							// tnagy reset form fields
							resetFormFields( $form, card );

							// tnagy show success message
							showSuccessGlobalMessage(
								$form,
								data.messageTitle,
								data.message
							);

							// tnagy enable submit button
							enableFormButtons( $form );
						}
					} else if ( data.requiresAction ) {
						handleStripeIntentAction( $form, card, data );
					} else {
						processValidationErrors( $form, data );
					}
				},
				error( jqXHR, textStatus, errorThrown ) {
					logError(
						'submitPaymentData',
						jqXHR,
						textStatus,
						errorThrown
					);
					showErrorGlobalMessage(
						$form,
						wpfsFormSettings.l10n.validation_errors
							.internal_error_title,
						wpfsFormSettings.l10n.validation_errors.internal_error
					);
				},
				complete() {
					enableFormButtons( $form );
					hideLoadingAnimation( $form );
				},
			} );
		}

		function handleStripeIntentAction( $form, card, data ) {
			function handlePaymentIntentAction( result ) {
				if ( debugLog ) {
					logInfo(
						'handlePaymentIntentAction',
						'result=' + JSON.stringify( result )
					);
				}
				if ( result.error ) {
					logWarn( 'handleStripeIntentAction', result.error.message );
					showErrorGlobalMessage(
						$form,
						wpfsFormSettings.l10n.validation_errors
							.internal_error_title,
						result.error.message
					);
				} else {
					removePaymentIntentIdInput( $form );
					addPaymentIntentIdInput( $form, result );
					disableFormButtons( $form );
					showLoadingAnimation( $form );
					submitPaymentData( $form, card );
				}
			}

			function handleSetupIntentAction( result ) {
				if ( debugLog ) {
					logInfo(
						'handleSetupIntentAction',
						'result=' + JSON.stringify( result )
					);
				}
				if ( result.error ) {
					logWarn( 'handleSetupIntentAction', result.error.message );
					showErrorGlobalMessage(
						$form,
						wpfsFormSettings.l10n.validation_errors
							.internal_error_title,
						result.error.message
					);
				} else {
					removeSetupIntentIdInput( $form );
					addSetupIntentIdInput( $form, result );
					disableFormButtons( $form );
					showLoadingAnimation( $form );
					submitPaymentData( $form, card );
				}
			}

			function handlePaymentSetupIntentAction( result ) {
				if ( debugLog ) {
					logInfo(
						'handlePaymentSetupIntentAction',
						'result=' + JSON.stringify( result )
					);
				}
				if ( result.error ) {
					logWarn(
						'handlePaymentSetupIntentAction',
						result.error.message
					);
					showErrorGlobalMessage(
						$form,
						wpfsFormSettings.l10n.validation_errors
							.internal_error_title,
						result.error.message
					);
				} else {
					removePaymentIntentIdInput( $form );
					removeSetupIntentIdInput( $form );
					addPaymentIntentIdInput( $form, result );
					addSetupIntentIdInput( $form, result );
					disableFormButtons( $form );
					showLoadingAnimation( $form );
					submitPaymentData( $form, card );
				}
			}

			if ( debugLog ) {
				logInfo(
					'handleStripeIntentAction',
					'CALLED, params: data=' + JSON.stringify( data )
				);
			}
			if ( stripe != null ) {
				if ( FORM_TYPE_INLINE_PAYMENT === data.formType ) {
					if ( data.isManualConfirmation ) {
						stripe
							.handleCardAction( data.paymentIntentClientSecret )
							.then( handlePaymentIntentAction );
					} else {
						stripe
							.confirmCardPayment(
								data.paymentIntentClientSecret
							)
							.then( handlePaymentIntentAction );
					}
				}
				if ( FORM_TYPE_INLINE_SUBSCRIPTION === data.formType ) {
					if (
						data.hasOwnProperty( 'paymentIntentClientSecret' ) &&
						data.paymentIntentClientSecret != null
					) {
						stripe
							.handleCardPayment( data.paymentIntentClientSecret )
							.then( handlePaymentSetupIntentAction );
					} else if (
						data.hasOwnProperty( 'setupIntentClientSecret' ) &&
						data.setupIntentClientSecret != null
					) {
						stripe
							.handleCardSetup( data.setupIntentClientSecret )
							.then( handlePaymentSetupIntentAction );
					}
				}
				if ( FORM_TYPE_INLINE_DONATION === data.formType ) {
					if ( data.isManualConfirmation ) {
						stripe
							.handleCardAction( data.paymentIntentClientSecret )
							.then( handlePaymentIntentAction );
					} else {
						stripe
							.confirmCardPayment(
								data.paymentIntentClientSecret
							)
							.then( handlePaymentIntentAction );
					}
				}
				if ( FORM_TYPE_INLINE_SAVE_CARD === data.formType ) {
					stripe
						.handleCardSetup( data.setupIntentClientSecret )
						.then( handleSetupIntentAction );
				}
			} else {
				// todo tnagy show error message
			}
		}

		function handleCustomAmountChange( $customAmountSelectElement, $form ) {
			const amountType = $form.data( 'wpfs-amount-type' );
			const amountValue = $customAmountSelectElement.val();

			if ( PAYMENT_TYPE_LIST_OF_AMOUNTS === amountType ) {
				if ( AMOUNT_OTHER === amountValue ) {
					clearAndShowAndEnableAndFocusCustomAmount( $form );
				} else {
					clearAndHideAndDisableCustomAmount( $form );
				}
			}
		}

		function parseCustomAmountUnique(
			$form,
			returnSmallestCommonCurrencyUnit = false
		) {
			let result = null;
			const formatter = createCurrencyFormatter( $form );
			const $element = $( '.wpfs-custom-amount--unique', $form );
			const customAmountValue = $element.val();

			if ( customAmountValue !== '' ) {
				const zeroDecimalSupport =
					$element.data( 'wpfs-zero-decimal-support' ) === true;

				result = parseCurrencyAmount(
					formatter.parse( customAmountValue ),
					zeroDecimalSupport,
					returnSmallestCommonCurrencyUnit
				);
			}

			return result;
		}

		function getTaxIdType( $form ) {
			return $( '.wpfs-tax-id-type-select option:selected', $form ).val();
		}

		/**
		 * Will return an object with the address fields from the form
		 * Shipping address will be used if it is present, otherwise billing address
		 * If the form does not have any address fields, null will be returned
		 *
		 * @param {Object} $form the form of the address fields
		 */
		function getTaxAddressFromForm( $form ) {
			const data = {};
			// capture shipping address if supplied
			const shipCityField = $(
				'input[name="wpfs-shipping-address-city"]',
				$form
			);
			const billCityField = $(
				'input[name="wpfs-billing-address-city"]',
				$form
			);
			if (
				shipCityField &&
				shipCityField.val() &&
				shipCityField.val().length > 0
			) {
				data.city = shipCityField.val();
				const shipLin1Field = $(
					'input[name="wpfs-shipping-address-line-1"]',
					$form
				);
				if (
					shipLin1Field &&
					shipLin1Field.val() &&
					shipLin1Field.val().length > 0
				) {
					data.line1 = shipLin1Field.val();
				}
				const shipLin2Field = $(
					'input[name="wpfs-shipping-address-line-2"]',
					$form
				);
				if (
					shipLin2Field &&
					shipLin2Field.val() &&
					shipLin2Field.val().length > 0
				) {
					data.line2 = shipLin2Field.val();
				}
			} else if (
				billCityField &&
				billCityField.val() &&
				billCityField.val().length > 0
			) {
				// otherwise use billing address if supplied
				data.city = billCityField.val();
				const billLin1Field = $(
					'input[name="wpfs-billing-address-line-1"]',
					$form
				);
				if (
					billLin1Field &&
					billLin1Field.val() &&
					billLin1Field.val().length > 0
				) {
					data.line1 = billLin1Field.val();
				}
				const billLin2Field = $(
					'input[name="wpfs-billing-address-line-2"]',
					$form
				);
				if (
					billLin2Field &&
					billLin2Field.val() &&
					billLin2Field.val().length > 0
				) {
					data.line2 = billLin2Field.val();
				}
			} else {
				data.city = null;
				data.line1 = null;
				data.line2 = null;
			}
			return data;
		}

		function gatherFormDataForTaxCalculation( $form ) {
			const data = {};

			data.currentPriceId = findPriceIdForSelectedProduct( $form );

			if (
				$( 'select[name="wpfs-tax-country"] option:selected', $form ) &&
				$( 'select[name="wpfs-tax-country"] option:selected', $form )
					.length > 0
			) {
				data.country = $(
					'select[name="wpfs-tax-country"] option:selected',
					$form
				).val();

				if (
					data.country === COUNTRY_CODE_UNITED_STATES &&
					$(
						'select[name="wpfs-tax-state"] option:selected',
						$form
					) &&
					$( 'select[name="wpfs-tax-state"] option:selected', $form )
						.length > 0
				) {
					data.state = $(
						'select[name="wpfs-tax-state"] option:selected',
						$form
					).val();
				} else {
					data.state = null;
				}
			} else if (
				$(
					'select[name="wpfs-billing-address-country"] option:selected',
					$form
				).length > 0
			) {
				data.country = $(
					'select[name="wpfs-billing-address-country"] option:selected',
					$form
				).val();

				if (
					data.country === COUNTRY_CODE_UNITED_STATES &&
					$(
						'select[name="wpfs-billing-address-state-select"] option:selected',
						$form
					).length > 0
				) {
					data.state = $(
						'select[name="wpfs-billing-address-state-select"] option:selected',
						$form
					).val();
				} else {
					data.state = null;
				}
			} else {
				data.country = null;
				data.state = null;
			}

			if (
				$( 'input[name="wpfs-tax-zip"]', $form ) &&
				$( 'input[name="wpfs-tax-zip"]', $form ).length > 0
			) {
				data.zip = $( 'input[name="wpfs-tax-zip"]', $form ).val();
			} else if (
				$( 'input[name="wpfs-billing-address-zip"]', $form ) &&
				$( 'input[name="wpfs-billing-address-zip"]', $form ).val() &&
				$( 'input[name="wpfs-billing-address-zip"]', $form ).val()
					.length > 0
			) {
				data.zip = $(
					'input[name="wpfs-billing-address-zip"]',
					$form
				).val();
			} else {
				data.zip = null;
			}

			const address = getTaxAddressFromForm( $form );
			data.city = address.city;
			data.line1 = address.line1;
			data.line2 = address.line2;

			if (
				$( 'input[name="wpfs-plan-quantity"]', $form ) &&
				$( 'input[name="wpfs-plan-quantity"]', $form ).length > 0
			) {
				data.quantity = $(
					'input[name="wpfs-plan-quantity"]',
					$form
				).spinner( 'value' );
			} else {
				data.quantity = 1;
			}

			if ( isBuyingAsABusiness( $form ) ) {
				data.taxIdType = getTaxIdType( $form );
				data.taxId = $( 'input[name="wpfs-tax-id"]', $form ).val();
			} else {
				data.taxIdType = null;
				data.taxId = null;
			}

			data.formId = extractFormNameFromNode( $form );

			data.formType = $form.data( FROM_TYPE_DOM );

			const coupon = WPFS.getCoupon( data.formId );
			if ( coupon !== null ) {
				data.coupon = coupon.name;
			} else {
				data.coupon = null;
			}

			data.customAmount = null;
			if (
				$( '.wpfs-custom-amount--unique', $form ) &&
				$( '.wpfs-custom-amount--unique', $form ).length > 0
			) {
				const customAmount = parseCustomAmountUnique( $form, true );

				if (
					customAmount &&
					customAmount !== '' &&
					! isNaN( customAmount )
				) {
					data.customAmount = customAmount;
				} else {
					data.customAmount = 0;
				}
			}

			return data;
		}

		function refreshShippingStateField( $form, country ) {
			if ( country === COUNTRY_CODE_UNITED_STATES ) {
				$( 'input[name="wpfs-shipping-address-state"]', $form ).hide();
				$(
					'.wpfs-form-select.wpfs-shipping-address-state-select'
				).show();
			} else {
				$( 'input[name="wpfs-shipping-address-state"]', $form ).show();
				$(
					'.wpfs-form-select.wpfs-shipping-address-state-select'
				).hide();
			}
		}

		function handleShippingAddressCountryChange(
			$shippingAddressSelectElement,
			$form
		) {
			if ( debugLog ) {
				console.log( 'handleShippingAddressCountryChange(): CALLED' );
			}

			const selectedCountry = $(
				'option:selected',
				$shippingAddressSelectElement
			).val();
			refreshShippingStateField( $form, selectedCountry );
		}

		function refreshBillingStateField( $form, country ) {
			if ( country === COUNTRY_CODE_UNITED_STATES ) {
				$( 'input[name="wpfs-billing-address-state"]', $form ).hide();
				$(
					'.wpfs-form-select.wpfs-billing-address-state-select'
				).show();
			} else {
				$( 'input[name="wpfs-billing-address-state"]', $form ).show();
				$(
					'.wpfs-form-select.wpfs-billing-address-state-select'
				).hide();
			}
		}

		function handleBillingAddressCountryChange(
			$billingAddressSelectElement,
			$form
		) {
			if ( debugLog ) {
				console.log( 'handleBillingAddressCountryChange(): CALLED' );
			}

			const selectedCountry = $(
				'option:selected',
				$billingAddressSelectElement
			).val();
			refreshBillingStateField( $form, selectedCountry );

			if ( isDynamicTaxesForm( $form ) || isStripeTaxForm( $form ) ) {
				refreshPricingFromServer( $form );
			}

			if ( isTaxIdCollectible( $form ) ) {
				setTaxIdTypeByCountry( $form );
				if ( isTaxIdCollectible( $form ) ) {
					refreshTaxIdPlaceholder( $form );
				}
			}
		}

		function handleBillingAddressStateChange(
			$billingAddressStateSelectElement,
			$form
		) {
			if ( isDynamicTaxesForm( $form ) ) {
				refreshPricingFromServer( $form );
			}
		}

		function findPlanElementByPlanId( $form, planId ) {
			let $planElement;
			if (
				$( '.wpfs-subscription-plan-select', $form ) &&
				$( '.wpfs-subscription-plan-select', $form ).length > 0
			) {
				$planElement = $(
					'select[name="wpfs-plan"] option[data-wpfs-value="' +
						planId +
						'"]',
					$form
				);
			} else if (
				$( '.wpfs-subscription-plan-radio', $form ) &&
				$( '.wpfs-subscription-plan-radio', $form ).length > 0
			) {
				$planElement = $(
					'input[name="wpfs-plan"][data-wpfs-value="' + planId + '"]',
					$form
				);
			} else if (
				$( 'input[name="wpfs-plan"]', $form ) &&
				$( 'input[name="wpfs-plan"]', $form ).length > 0
			) {
				$planElement = $(
					'input[name="wpfs-plan"][data-wpfs-value="' + planId + '"]',
					$form
				);
			} else {
				logError(
					'handlePlanChange',
					'WARNING: Subscription Plan Selector UI element not found!'
				);
			}
			return $planElement;
		}

		function handleAmountChange( $form ) {
			if ( debugLog ) {
				console.log( 'handleAmountChange(): CALLED' );
			}
			if (
				PAYMENT_TYPE_LIST_OF_AMOUNTS ===
				$form.data( 'wpfs-amount-type' )
			) {
				handlePaymentListOfAmountsChange( $form );
			} else if (
				PAYMENT_TYPE_CUSTOM_AMOUNT === $form.data( 'wpfs-amount-type' )
			) {
				handlePaymentCustomAmountChange( $form );
			} else if (
				PAYMENT_TYPE_SPECIFIED_AMOUNT ===
				$form.data( 'wpfs-amount-type' )
			) {
				handlePaymentHiddenChange( $form );
			} else {
				logWarn(
					'handlePaymentChange()',
					'Payment Amount UI element not found!'
				);
			}
		}

		function handlePaymentListOfAmountsChange( $form ) {
			if ( debugLog ) {
				console.log( 'handlePaymentListOfAmountsChange(): CALLED' );
			}

			let $selectedAmountElement;
			if (
				$( '.wpfs-custom-amount-select', $form ) &&
				$( '.wpfs-custom-amount-select', $form ).length > 0 &&
				$( '.wpfs-custom-amount-select option:selected', $form ) &&
				$( '.wpfs-custom-amount-select option:selected', $form )
					.length > 0
			) {
				$selectedAmountElement = $(
					'.wpfs-custom-amount-select option:selected',
					$form
				);
			} else if (
				$( 'input.wpfs-custom-amount', $form ) &&
				$( 'input.wpfs-custom-amount', $form ).length > 0
			) {
				$selectedAmountElement = $(
					'input.wpfs-custom-amount:checked',
					$form
				);
			}
			if (
				$selectedAmountElement &&
				$selectedAmountElement.val() === AMOUNT_OTHER
			) {
				$selectedAmountElement = $(
					'input.wpfs-custom-amount--unique',
					$form
				);
			}

			if ( $selectedAmountElement ) {
				handlePaymentAmountChange( $form, $selectedAmountElement );
			}
		}

		function handlePaymentCustomAmountChange( $form ) {
			const $selectedAmountElement = $(
				'.wpfs-custom-amount--unique',
				$form
			);
			handlePaymentAmountChange( $form, $selectedAmountElement );
		}

		function handlePaymentHiddenChange( $form ) {
			const $selectedAmountElement = $( '.wpfs-custom-amount', $form );
			handlePaymentAmountChange( $form, $selectedAmountElement );
		}

		function getTotalAmountForPrice( paymentDetailsConfig ) {
			let total = 0;

			if ( paymentDetailsConfig.hasOwnProperty( 'total' ) ) {
				total = paymentDetailsConfig.total;
			} else {
				const paymentDetails = WPFS.getPaymentDetailsForPrice(
					paymentDetailsConfig.formId,
					paymentDetailsConfig.priceId
				);

				if ( paymentDetails !== null ) {
					paymentDetails.forEach( ( item ) => {
						if (
							! (
								item.subType === PRICE_LINE_ITEM_SUBTYPE_TAX &&
								item.inclusive
							)
						) {
							total += item.amount;
						}
					} );
				}
			}

			const amountLabel = formatCurrencyAmount(
				total,
				paymentDetailsConfig.zeroDecimalSupport
			);
			return parseCurrencyAmount(
				amountLabel,
				paymentDetailsConfig.zeroDecimalSupport
			);
		}

		function showPaymentButtonCaption( $form, paymentDetailsConfig ) {
			const total = getTotalAmountForPrice( paymentDetailsConfig );

			if ( total === null || isNaN( total ) ) {
				updateButtonCaption(
					$form,
					paymentDetailsConfig.buttonTitle,
					null,
					null,
					null,
					null
				);
			} else {
				updateButtonCaption(
					$form,
					paymentDetailsConfig.buttonTitle,
					paymentDetailsConfig.currency,
					paymentDetailsConfig.currencySymbol,
					paymentDetailsConfig.zeroDecimalSupport
						? total
						: total.toFixed( 2 ),
					paymentDetailsConfig.zeroDecimalSupport
				);
			}
		}

		function renderSubscriptionSummary(
			interval,
			intervalCount,
			cancellationCount
		) {
			let subscriptionDetailsLabel = null;

			if ( cancellationCount > 0 ) {
				if ( intervalCount > 1 ) {
					if ( 'day' === interval ) {
						subscriptionDetailsLabel = vsprintf(
							wpfsFormSettings.l10n
								.subscription_charge_interval_templates
								.x_times_y_days,
							[ intervalCount, cancellationCount ]
						);
					} else if ( 'week' === interval ) {
						subscriptionDetailsLabel = vsprintf(
							wpfsFormSettings.l10n
								.subscription_charge_interval_templates
								.x_times_y_weeks,
							[ intervalCount, cancellationCount ]
						);
					} else if ( 'month' === interval ) {
						subscriptionDetailsLabel = vsprintf(
							wpfsFormSettings.l10n
								.subscription_charge_interval_templates
								.x_times_y_months,
							[ intervalCount, cancellationCount ]
						);
					} else if ( 'year' === interval ) {
						subscriptionDetailsLabel = vsprintf(
							wpfsFormSettings.l10n
								.subscription_charge_interval_templates
								.x_times_y_years,
							[ intervalCount, cancellationCount ]
						);
					}
				} else if ( 'day' === interval ) {
					subscriptionDetailsLabel = vsprintf(
						wpfsFormSettings.l10n
							.subscription_charge_interval_templates
							.x_times_daily,
						[ cancellationCount ]
					);
				} else if ( 'week' === interval ) {
					subscriptionDetailsLabel = vsprintf(
						wpfsFormSettings.l10n
							.subscription_charge_interval_templates
							.x_times_weekly,
						[ cancellationCount ]
					);
				} else if ( 'month' === interval ) {
					subscriptionDetailsLabel = vsprintf(
						wpfsFormSettings.l10n
							.subscription_charge_interval_templates
							.x_times_monthly,
						[ cancellationCount ]
					);
				} else if ( 'year' === interval ) {
					subscriptionDetailsLabel = vsprintf(
						wpfsFormSettings.l10n
							.subscription_charge_interval_templates
							.x_times_yearly,
						[ cancellationCount ]
					);
				}
			} else if ( intervalCount > 1 ) {
				if ( 'day' === interval ) {
					subscriptionDetailsLabel = vsprintf(
						wpfsFormSettings.l10n
							.subscription_charge_interval_templates.y_days,
						[ intervalCount ]
					);
				} else if ( 'week' === interval ) {
					subscriptionDetailsLabel = vsprintf(
						wpfsFormSettings.l10n
							.subscription_charge_interval_templates.y_weeks,
						[ intervalCount ]
					);
				} else if ( 'month' === interval ) {
					subscriptionDetailsLabel = vsprintf(
						wpfsFormSettings.l10n
							.subscription_charge_interval_templates.y_months,
						[ intervalCount ]
					);
				} else if ( 'year' === interval ) {
					subscriptionDetailsLabel = vsprintf(
						wpfsFormSettings.l10n
							.subscription_charge_interval_templates.y_years,
						[ intervalCount ]
					);
				}
			} else if ( 'day' === interval ) {
				subscriptionDetailsLabel =
					wpfsFormSettings.l10n.subscription_charge_interval_templates
						.daily;
			} else if ( 'week' === interval ) {
				subscriptionDetailsLabel =
					wpfsFormSettings.l10n.subscription_charge_interval_templates
						.weekly;
			} else if ( 'month' === interval ) {
				subscriptionDetailsLabel =
					wpfsFormSettings.l10n.subscription_charge_interval_templates
						.monthly;
			} else if ( 'year' === interval ) {
				subscriptionDetailsLabel =
					wpfsFormSettings.l10n.subscription_charge_interval_templates
						.yearly;
			}

			return subscriptionDetailsLabel;
		}

		function showPaymentDetails( $form, paymentDetailsConfig ) {
			const lineItems = WPFS.getPaymentDetailsForPrice(
				paymentDetailsConfig.formId,
				paymentDetailsConfig.priceId
			);

			renderPaymentDetails(
				$form,
				paymentDetailsConfig,
				lineItems,
				null
			);
		}

		function showSubscriptionDetails( $form, paymentDetailsConfig ) {
			const lineItems = WPFS.getPaymentDetailsForPrice(
				paymentDetailsConfig.formId,
				paymentDetailsConfig.priceId
			);
			const subscriptionDetailsLabel = renderSubscriptionSummary(
				paymentDetailsConfig.interval,
				paymentDetailsConfig.intervalCount,
				paymentDetailsConfig.cancellationCount
			);

			renderPaymentDetails(
				$form,
				paymentDetailsConfig,
				lineItems,
				subscriptionDetailsLabel
			);
		}

		function preparePaymentDetails( lineItems ) {
			const displayItems = [];

			const setupFeeItem = lineItems.find(
				( lineItem ) =>
					lineItem.type === PRICE_LINE_ITEM_TYPE_SETUP_FEE &&
					lineItem.subType === PRICE_LINE_ITEM_TYPE_SETUP_FEE
			);
			if ( setupFeeItem ) {
				displayItems.push( {
					type: PAYMENT_DETAIL_ROW_SETUP_FEE,
					amount: setupFeeItem.amount,
					displayName: setupFeeItem.displayName,
				} );
			}

			const productItem = lineItems.find(
				( lineItem ) =>
					lineItem.type === PRICE_LINE_ITEM_TYPE_PRODUCT &&
					lineItem.subType === PRICE_LINE_ITEM_TYPE_PRODUCT
			);
			if ( productItem ) {
				displayItems.push( {
					type: PAYMENT_DETAIL_ROW_PRODUCT,
					amount: productItem.amount,
					displayName: productItem.displayName,
				} );
			}

			const taxItems = lineItems.filter(
				( lineItem ) => lineItem.subType === PRICE_LINE_ITEM_SUBTYPE_TAX
			);
			if ( taxItems && taxItems.length > 0 ) {
				const taxRateIds = [
					...new Set( taxItems.map( ( taxItem ) => taxItem.id ) ),
				];
				const taxRateAmounts = {};
				taxRateIds.forEach( ( taxRateId ) => {
					taxRateAmounts[ taxRateId ] = 0;
				} );
				taxItems.forEach( ( taxItem ) => {
					taxRateAmounts[ taxItem.id ] += taxItem.amount;
				} );

				for ( const taxRateId in taxRateAmounts ) {
					const taxItem = taxItems.find(
						( taxItem ) => taxItem.id === taxRateId
					);

					displayItems.push( {
						type: PAYMENT_DETAIL_ROW_TAX,
						percentage: taxItem.percentage,
						amount: taxRateAmounts[ taxRateId ],
						inclusive: taxItem.inclusive,
						displayName: taxItem.displayName
							? taxItem.displayName
							: wpfsFormSettings.l10n.product_pricing
									.default_tax_label,
					} );
				}
			}

			const discountAmount = lineItems
				.filter(
					( lineItem ) =>
						lineItem.subType === PRICE_LINE_ITEM_SUBTYPE_DISCOUNT
				)
				.reduce( ( sum, lineItem ) => {
					return sum + lineItem.amount;
				}, 0 );
			if ( discountAmount < 0 ) {
				const labelItem = lineItems.find(
					( lineItem ) =>
						lineItem.subType === PRICE_LINE_ITEM_SUBTYPE_DISCOUNT
				);

				displayItems.push( {
					type: PAYMENT_DETAIL_ROW_DISCOUNT,
					amount: discountAmount,
					displayName: labelItem
						? labelItem.displayName
						: wpfsFormSettings.l10n.product_pricing
								.default_coupon_label,
				} );
			}

			return displayItems;
		}

		function getTaxLineItemLabel( lineItem ) {
			let result;

			if ( lineItem.percentage ) {
				const pattern = lineItem.inclusive
					? wpfsFormSettings.l10n.product_pricing
							.tax_label_inclusive_percentage
					: wpfsFormSettings.l10n.product_pricing
							.tax_label_percentage;
				result = sprintf(
					pattern,
					lineItem.displayName,
					lineItem.percentage
				);
			} else {
				const pattern = lineItem.inclusive
					? wpfsFormSettings.l10n.product_pricing.tax_label_inclusive
					: wpfsFormSettings.l10n.product_pricing.tax_label;
				result = sprintf( pattern, lineItem.displayName );
			}

			return result;
		}

		function renderPaymentDetails(
			$form,
			paymentDetailsConfig,
			lineItems,
			summaryLabel
		) {
			clearPaymentDetails( $form );

			if ( lineItems !== null ) {
				const displayItems = preparePaymentDetails( lineItems );

				const formatter = createCurrencyFormatter( $form );

				let total = 0;
				let taxIdx = 0;
				displayItems.forEach( ( lineItem ) => {
					if (
						PRICE_ID_CUSTOM_AMOUNT !== paymentDetailsConfig.priceId
					) {
						lineItem.amount = lineItem.amount / 100;
					}
					const amount = formatter.format(
						formatCurrencyAmount(
							lineItem.amount,
							paymentDetailsConfig.zeroDecimalSupport
						),
						paymentDetailsConfig.currency.toUpperCase(),
						paymentDetailsConfig.currencySymbol,
						paymentDetailsConfig.zeroDecimalSupport
					);

					if (
						lineItem.type === PAYMENT_DETAIL_ROW_PRODUCT ||
						lineItem.type === PAYMENT_DETAIL_ROW_SETUP_FEE ||
						lineItem.type === PAYMENT_DETAIL_ROW_DISCOUNT
					) {
						$(
							'td[data-wpfs-summary-row-label="' +
								lineItem.type +
								'"]',
							$form
						).text( lineItem.displayName );
						$(
							'td[data-wpfs-summary-row-value="' +
								lineItem.type +
								'"]',
							$form
						).text( amount );
						$(
							'tr[data-wpfs-summary-row="' + lineItem.type + '"]',
							$form
						).show();

						total += lineItem.amount;
					} else if ( lineItem.type === PAYMENT_DETAIL_ROW_TAX ) {
						$(
							'td[data-wpfs-summary-row-label="tax-' +
								taxIdx +
								'"]',
							$form
						).text( getTaxLineItemLabel( lineItem ) );
						$(
							'td[data-wpfs-summary-row-value="tax-' +
								taxIdx +
								'"]',
							$form
						).text( amount );
						$(
							'tr[data-wpfs-summary-row="tax-' + taxIdx + '"]',
							$form
						).show();

						if ( ! lineItem.inclusive ) {
							total += lineItem.amount;
						}

						taxIdx++;
					}
				} );

				const totalLabel = formatter.format(
					formatCurrencyAmount(
						total,
						paymentDetailsConfig.zeroDecimalSupport
					),
					paymentDetailsConfig.currency.toUpperCase(),
					paymentDetailsConfig.currencySymbol,
					paymentDetailsConfig.zeroDecimalSupport
				);
				$(
					'td[data-wpfs-summary-row-value="' +
						PAYMENT_DETAIL_ROW_TOTAL +
						'"]',
					$form
				).text( totalLabel );
				$(
					'tr[data-wpfs-summary-row="' +
						PAYMENT_DETAIL_ROW_TOTAL +
						'"]',
					$form
				).show();
			}

			if ( summaryLabel !== null ) {
				$( '.wpfs-summary-description', $form ).text( summaryLabel );
				$( '.wpfs-summary-description', $form ).show();
			}
		}

		function handleDonationPaymentAmountChange(
			$form,
			$selectedProductElement
		) {
			const buttonCaptionConfig = {
				formId: extractFormNameFromNode( $form ),
				currency: $selectedProductElement.data( 'wpfs-currency' ),
				currencySymbol: $selectedProductElement.data(
					'wpfs-currency-symbol'
				),
				zeroDecimalSupport:
					$selectedProductElement.data(
						'wpfs-zero-decimal-support'
					) === true,
				buttonTitle:
					$selectedProductElement.data( 'wpfs-button-title' ),
			};
			if (
				$selectedProductElement.hasClass( 'wpfs-custom-amount--unique' )
			) {
				buttonCaptionConfig.total = parseCurrencyAmount(
					$selectedProductElement.val()
						? $selectedProductElement.val()
						: 0,
					buttonCaptionConfig.zeroDecimalSupport,
					true
				);
			} else {
				buttonCaptionConfig.total = $selectedProductElement.data(
					'wpfs-amount-in-smallest-common-currency'
				);
			}

			showPaymentButtonCaption( $form, buttonCaptionConfig );
		}

		function handlePriceAwarePaymentAmountChange(
			$form,
			$selectedProductElement
		) {
			if ( isCouponFieldVisible( $form ) ) {
				refreshCouponField( $form );
			}

			// update pricing to update payment intent
			if ( $form.data( 'wpfs-stripe-payment-intent-id' ) ) {
				const data = gatherFormDataForTaxCalculation( $form );

				data.action = 'wpfs-update-payment-intent';
				data.stripePaymentIntentId = $form.data(
					'wpfs-stripe-payment-intent-id'
				);
				data.stripePriceId = $selectedProductElement.data(
					'wpfs-amount-price-id'
				);
				$.ajax( {
					type: 'POST',
					url: wpfsFormSettings.ajaxUrl,
					data,
					cache: false,
					dataType: 'json',
					success( data ) {
						if ( debugLog ) {
							logInfo(
								'handlePriceAwarePaymentAmountChange',
								'SUCCESS response=' + JSON.stringify( data )
							);
						}
					},
					error( jqXHR, textStatus, errorThrown ) {
						logError(
							'handlePriceAwarePaymentAmountChange',
							jqXHR,
							textStatus,
							errorThrown
						);
						showErrorGlobalMessage(
							$form,
							wpfsFormSettings.l10n.validation_errors
								.internal_error_title,
							wpfsFormSettings.l10n.validation_errors
								.internal_error
						);
					},
				} );
			}

			const buttonCaptionConfig = {
				priceId: $selectedProductElement.data( 'wpfs-amount-price-id' ),
				formId: extractFormNameFromNode( $form ),
				currency: $selectedProductElement.data( 'wpfs-currency' ),
				currencySymbol: $selectedProductElement.data(
					'wpfs-currency-symbol'
				),
				zeroDecimalSupport:
					$selectedProductElement.data(
						'wpfs-zero-decimal-support'
					) === true,
				buttonTitle:
					$selectedProductElement.data( 'wpfs-button-title' ),
			};
			showPaymentButtonCaption( $form, buttonCaptionConfig );

			const paymentDetailsConfig = {
				priceId: $selectedProductElement.data( 'wpfs-amount-price-id' ),
				formId: extractFormNameFromNode( $form ),
				currency: $selectedProductElement.data( 'wpfs-currency' ),
				currencySymbol: $selectedProductElement.data(
					'wpfs-currency-symbol'
				),
				zeroDecimalSupport:
					$selectedProductElement.data(
						'wpfs-zero-decimal-support'
					) === true,
			};
			showPaymentDetails( $form, paymentDetailsConfig );
		}

		function handlePaymentAmountChange( $form, $selectedProductElement ) {
			if ( isDonationForm( $form ) ) {
				handleDonationPaymentAmountChange(
					$form,
					$selectedProductElement
				);
			} else {
				handlePriceAwarePaymentAmountChange(
					$form,
					$selectedProductElement
				);
			}
		}

		function getSelectedProductName( $form ) {
			const priceId = findPriceIdForSelectedProduct( $form );
			const pricingData = WPFS.getPaymentDetails(
				extractFormNameFromNode( $form )
			);

			if ( pricingData ) {
				if ( pricingData.hasOwnProperty( priceId ) ) {
					const filteredItems = pricingData[ priceId ].filter(
						( item ) => item.type === PRICE_LINE_ITEM_TYPE_PRODUCT
					);
					if ( filteredItems.length > 0 ) {
						return filteredItems[ 0 ].displayName;
					}
				}
			}

			return wpfsFormSettings.l10n.products.the_selected_product_label;
		}

		function handlePlanChange( $form ) {
			if ( debugLog ) {
				console.log( 'handlePlanChange(): CALLED' );
			}
			if (
				$( '.wpfs-subscription-plan-select', $form ) &&
				$( '.wpfs-subscription-plan-select', $form ).length > 0
			) {
				handlePlanSelectChange( $form );
			} else if (
				$( '.wpfs-subscription-plan-radio', $form ) &&
				$( '.wpfs-subscription-plan-radio', $form ).length > 0
			) {
				handlePlanRadioButtonChange( $form );
			} else if (
				$( '.wpfs-subscription-plan-hidden', $form ) &&
				$( '.wpfs-subscription-plan-hidden', $form ).length > 0
			) {
				handlePlanHiddenChange( $form );
			} else {
				logWarn(
					'handlePlanChange()',
					'Subscription Plan Selector UI element not found!'
				);
			}
		}

		function handlePlanSelectChange( $form ) {
			if ( debugLog ) {
				console.log( 'handlePlanSelectChange(): CALLED' );
			}
			const $subscriptionPlanSelectElement = $(
				'.wpfs-subscription-plan-select',
				$form
			);
			const selectedPlanId = $subscriptionPlanSelectElement.val();
			const $selectedPlanElement = $(
				'option[value="' + selectedPlanId + '"]',
				$form
			);
			handleSubscriptionPlanChange( $form, $selectedPlanElement );
		}

		function handlePlanRadioButtonChange( $form ) {
			if ( debugLog ) {
				console.log( 'handlePlanRadioButtonChange(): CALLED' );
			}
			const $selectedPlanElement = $(
				'.wpfs-subscription-plan-radio:checked',
				$form
			);
			if ( debugLog ) {
				console.log(
					'handlePlanRadioButtonChange(): selectedPlanElement=' +
						JSON.stringify( $selectedPlanElement )
				);
			}
			handleSubscriptionPlanChange( $form, $selectedPlanElement );
		}

		function handlePlanHiddenChange( $form ) {
			if ( debugLog ) {
				console.log( 'handlePlanHiddenChange(): CALLED' );
			}
			const $selectedPlanElement = $(
				'.wpfs-subscription-plan-hidden',
				$form
			);
			if ( debugLog ) {
				console.log(
					'handlePlanHiddenChange(): selectedPlanElement=' +
						JSON.stringify( $selectedPlanElement )
				);
			}
			handleSubscriptionPlanChange( $form, $selectedPlanElement );
		}

		function refreshCouponField( $form ) {
			const coupon = WPFS.getCoupon( extractFormNameFromNode( $form ) );

			if ( coupon ) {
				hideCouponToRedeemRow( $form );
				if ( isSelectedProductDiscounted( $form ) ) {
					showRedeemedCouponRow( $form, coupon.name );
					hideWrongRedeemedCouponRow( $form );
				} else {
					hideRedeemedCouponRow( $form );
					showWrongRedeemedCouponRow(
						$form,
						coupon.name,
						getSelectedProductName( $form )
					);
				}
			} else {
				showCouponToRedeemRow( $form );
			}
		}

		function handleSubscriptionPlanChange( $form, $selectedPlanElement ) {
			if ( debugLog ) {
				console.log( 'handleSubscriptionPlanChange(): CALLED' );
			}

			if ( isCouponFieldVisible( $form ) ) {
				refreshCouponField( $form );
			}

			const paymentDetailsConfig = {
				priceId: $selectedPlanElement.data( 'wpfs-value' ),
				formId: extractFormNameFromNode( $form ),
				currency: $selectedPlanElement.data( 'wpfs-currency' ),
				currencySymbol: $selectedPlanElement.data(
					'wpfs-currency-symbol'
				),
				zeroDecimalSupport:
					$selectedPlanElement.data( 'wpfs-zero-decimal-support' ) ===
					true,
				interval: $selectedPlanElement.attr( 'data-wpfs-interval' ),
				intervalCount: parseInt(
					$selectedPlanElement.attr( 'data-wpfs-interval-count' )
				),
				cancellationCount: parseInt(
					$selectedPlanElement.attr( 'data-wpfs-cancellation-count' )
				),
			};
			showSubscriptionDetails( $form, paymentDetailsConfig );
		}

		function showCouponToRedeemRow( $form ) {
			$( '.wpfs-coupon-to-redeem-row', $form ).show();
		}

		function hideCouponToRedeemRow( $form ) {
			$( '.wpfs-coupon-to-redeem-row', $form ).hide();
		}

		function showWrongRedeemedCouponRow(
			$form,
			couponName,
			productOrServiceName
		) {
			const wrongRedeemedCouponLabelPattern = $(
				'.wpfs-coupon-wrong-redeemed-label',
				$form
			).data( 'wpfs-coupon-wrong-redeemed-label' );
			const wrongRedeemedCouponLabel = vsprintf(
				wrongRedeemedCouponLabelPattern,
				[ couponName, productOrServiceName ]
			);
			if ( debugLog ) {
				console.log(
					'showWrongRedeemedCouponRow(): redeemCouponLabelPattern=' +
						wrongRedeemedCouponLabelPattern +
						', couponName=' +
						couponName +
						', productOrServiceName=' +
						productOrServiceName +
						', redeemCouponLabel=' +
						wrongRedeemedCouponLabel
				);
			}
			$( '.wpfs-coupon-wrong-redeemed-label', $form ).html(
				wrongRedeemedCouponLabel
			);
			$( '.wpfs-coupon-wrong-redeemed-row', $form ).show();
		}

		function hideWrongRedeemedCouponRow( $form ) {
			$( '.wpfs-coupon-wrong-redeemed-label', $form ).html( '' );
			$( '.wpfs-coupon-wrong-redeemed-row', $form ).hide();
		}

		function showRedeemedCouponRow( $form, couponName ) {
			const redeemedCouponLabelPattern = $(
				'.wpfs-coupon-redeemed-label',
				$form
			).data( 'wpfs-coupon-redeemed-label' );
			const redeemedCouponLabel = vsprintf( redeemedCouponLabelPattern, [
				couponName,
			] );
			if ( debugLog ) {
				console.log(
					'showRedeemedCouponRow(): redeemCouponLabelPattern=' +
						redeemedCouponLabelPattern +
						', couponName=' +
						couponName +
						', redeemCouponLabel=' +
						redeemedCouponLabel
				);
			}
			$( '.wpfs-coupon-redeemed-label', $form ).html(
				redeemedCouponLabel
			);
			$( '.wpfs-coupon-redeemed-row', $form ).show();
		}

		function hideRedeemedCouponRow( $form ) {
			$( '.wpfs-coupon-redeemed-label', $form ).html( '' );
			$( '.wpfs-coupon-redeemed-row', $form ).hide();
		}

		function showRedeemLoadingAnimation( $form ) {
			const $redeemLink = $( '.wpfs-coupon-redeem-link', $form );
			$redeemLink.blur();
			if ( $redeemLink.hasClass( 'wpfs-input-group-link--loader' ) ) {
				return;
			}
			$redeemLink.addClass( 'wpfs-input-group-link--loader' );
		}

		function hideRedeemLoadingAnimation( $form ) {
			const $redeemLink = $( '.wpfs-coupon-redeem-link', $form );
			$redeemLink.blur();
			$redeemLink.removeClass( 'wpfs-input-group-link--loader' );
		}

		function findPaymentAmountData( $form ) {
			const result = {};
			let currency;
			let amount;
			const amountType = $form.data( 'wpfs-amount-type' );
			const amountSelectorStyle = $form.data( 'wpfs-selector-style' );
			let zeroDecimalSupport;
			const returnSmallestCommonCurrencyUnit = true;
			let customAmountValue;
			let formatter;
			if ( debugLog ) {
				console.log(
					'findPaymentAmountData(): ' +
						'amountType=' +
						amountType +
						', ' +
						'amountSelectorStyle=' +
						amountSelectorStyle
				);
			}
			if ( PAYMENT_TYPE_SPECIFIED_AMOUNT === amountType ) {
				result.paymentType = PAYMENT_TYPE_SPECIFIED_AMOUNT;
				result.customAmount = false;
				currency = $form.data( 'wpfs-currency' );
				amount = parseInt( $form.data( 'wpfs-amount' ) );
			} else if ( PAYMENT_TYPE_LIST_OF_AMOUNTS === amountType ) {
				result.paymentType = PAYMENT_TYPE_LIST_OF_AMOUNTS;
				const $listOfAmounts = findListOfAmountsElement( $form );
				currency = $listOfAmounts.data( 'wpfs-currency' );
				zeroDecimalSupport =
					true === $listOfAmounts.data( 'wpfs-zero-decimal-support' );
				const allowCustomAmountValue =
					1 == $form.data( 'wpfs-allow-list-of-amounts-custom' );
				const $selectedAmount =
					findSelectedAmountFromListOfAmounts( $form );
				if ( $selectedAmount && $selectedAmount.length > 0 ) {
					amount = $selectedAmount.val();
				}
				if ( debugLog ) {
					console.log(
						'findPaymentAmountData(): ' +
							'zeroDecimalSupport=' +
							zeroDecimalSupport +
							', ' +
							'allowCustomAmountValue=' +
							allowCustomAmountValue +
							', ' +
							'amount=' +
							amount
					);
				}
				if ( allowCustomAmountValue && AMOUNT_OTHER == amount ) {
					result.customAmount = true;
					customAmountValue = $(
						'input[name="wpfs-custom-amount-unique"]',
						$form
					).val();
					formatter = createCurrencyFormatter( $form );
					if ( formatter.validForParse( customAmountValue ) ) {
						clearFieldError(
							$form,
							'wpfs-custom-amount-unique',
							$(
								'input[name="wpfs-custom-amount-unique"]',
								$form
							).attr( 'id' )
						);
						amount = parseCurrencyAmount(
							formatter.parse( customAmountValue ),
							zeroDecimalSupport,
							returnSmallestCommonCurrencyUnit
						);
					} else {
						amount = null;
						clearFieldError(
							$form,
							'wpfs-custom-amount-unique',
							$(
								'input[name="wpfs-custom-amount-unique"]',
								$form
							).attr( 'id' )
						);
						showFieldError(
							$form,
							'wpfs-custom-amount-unique',
							$(
								'input[name="wpfs-custom-amount-unique"]',
								$form
							).attr( 'id' ),
							wpfsFormSettings.l10n.validation_errors
								.custom_payment_amount_value_is_invalid,
							true
						);
					}
					if ( debugLog ) {
						console.log(
							'findPaymentAmountData(): ' +
								'custom amount=' +
								amount
						);
					}
				} else {
					result.customAmount = false;
					amount = parseCurrencyAmount(
						$selectedAmount.val(),
						zeroDecimalSupport,
						returnSmallestCommonCurrencyUnit
					);
				}
			} else if ( PAYMENT_TYPE_CUSTOM_AMOUNT == amountType ) {
				result.customAmount = true;
				const customAmountElement = findCustomAmountElement( $form );
				currency = customAmountElement.data( 'wpfs-currency' );
				zeroDecimalSupport =
					true ===
					customAmountElement.data( 'wpfs-zero-decimal-support' );
				customAmountValue = customAmountElement.val();
				formatter = createCurrencyFormatter( $form );
				if ( formatter.validForParse( customAmountValue ) ) {
					clearFieldError(
						$form,
						'wpfs-custom-amount-unique',
						$(
							'input[name="wpfs-custom-amount-unique"]',
							$form
						).attr( 'id' )
					);
					amount = parseCurrencyAmount(
						formatter.parse( customAmountValue ),
						zeroDecimalSupport,
						returnSmallestCommonCurrencyUnit
					);
				} else {
					amount = null;
					clearFieldError(
						$form,
						'wpfs-custom-amount-unique',
						$(
							'input[name="wpfs-custom-amount-unique"]',
							$form
						).attr( 'id' )
					);
					showFieldError(
						$form,
						'wpfs-custom-amount-unique',
						$(
							'input[name="wpfs-custom-amount-unique"]',
							$form
						).attr( 'id' ),
						wpfsFormSettings.l10n.validation_errors
							.custom_payment_amount_value_is_invalid,
						true
					);
				}
			}
			if ( amount === null || isNaN( amount ) || amount < 0 ) {
				result.valid = false;
			} else {
				result.valid = true;
				result.currency = currency;
				result.amount = amount;
			}

			if ( debugLog ) {
				console.log(
					'findPaymentAmountData(): ' +
						'result=' +
						JSON.stringify( result )
				);
			}

			return result;
		}

		function findSelectedOneTimeProductNode( $form ) {
			let $selectedProduct;

			const amountSelectorStyle = $form.data( 'wpfs-selector-style' );
			if ( SELECTOR_STYLE_RADIO_BUTTONS === amountSelectorStyle ) {
				$selectedProduct = $form.find(
					'input[name="wpfs-custom-amount"]:checked'
				);
			} else if ( SELECTOR_STYLE_BUTTON_GROUP === amountSelectorStyle ) {
				$selectedProduct = $form.find(
					'input[name="wpfs-custom-amount"]:checked'
				);
			} else if ( SELECTOR_STYLE_DROPDOWN === amountSelectorStyle ) {
				$selectedProduct = $form.find(
					'select[name="wpfs-custom-amount"] option:selected'
				);
			} else {
				$selectedProduct = $form.find(
					'input[name="wpfs-custom-amount-unique"]'
				);
				if ( $selectedProduct.length === 0 ) {
					$selectedProduct = $form.find(
						'input[name="wpfs-custom-amount"]'
					);
				}
				if ( $selectedProduct.length === 0 ) {
					logWarn(
						'findSelectedOneTimeProductNode(): Cannot find product node'
					);
				}
			}

			return $selectedProduct;
		}

		function findSelectedPlanNode( $form ) {
			let $selectedPlan;

			if ( 1 == $form.data( 'wpfs-simple-button-layout' ) ) {
				$selectedPlan = $( 'input[name="wpfs-plan"]', $form );
			} else if (
				$( '.wpfs-subscription-plan-select', $form ) &&
				$( '.wpfs-subscription-plan-select', $form ).length > 0
			) {
				$selectedPlan = $(
					'select[name="wpfs-plan"] option:selected',
					$form
				);
			} else if (
				$( '.wpfs-subscription-plan-radio', $form ) &&
				$( '.wpfs-subscription-plan-radio', $form ).length > 0
			) {
				$selectedPlan = $(
					'.wpfs-subscription-plan-radio:checked',
					$form
				);
			} else if (
				$( '.wpfs-subscription-plan-hidden', $form ) &&
				$( '.wpfs-subscription-plan-hidden', $form ).length > 0
			) {
				$selectedPlan = $( '.wpfs-subscription-plan-hidden', $form );
			} else {
				logError(
					'findSelectedPlanNode',
					null,
					'WARNING: Subscription Plan Selector UI element not found!'
				);
			}

			return $selectedPlan;
		}

		function findPriceIdForSelectedOneTimeProduct( $form ) {
			let result = null;

			const $selectedProduct = findSelectedOneTimeProductNode( $form );
			if ( $selectedProduct && $selectedProduct.length === 1 ) {
				result = $selectedProduct.data( 'wpfs-amount-price-id' );
			}

			return result;
		}

		function findPriceIdForSelectedPlan( $form ) {
			let result = null;

			const $selectedPlan = findSelectedPlanNode( $form );
			if ( $selectedPlan && $selectedPlan.length === 1 ) {
				result = $selectedPlan.data( 'wpfs-value' );
			}

			return result;
		}

		function findPriceIdForSelectedProduct( $form ) {
			const formType = $form.data( FROM_TYPE_DOM );

			if (
				formType === FORM_TYPE_INLINE_PAYMENT ||
				formType === FORM_TYPE_CHECKOUT_PAYMENT
			) {
				return findPriceIdForSelectedOneTimeProduct( $form );
			} else if (
				formType === FORM_TYPE_INLINE_SUBSCRIPTION ||
				formType === FORM_TYPE_CHECKOUT_SUBSCRIPTION
			) {
				return findPriceIdForSelectedPlan( $form );
			}

			throw new Error(
				'findPriceIdForSelectedProduct() - Unknown form type: ' +
					formType
			);
		}

		function findPlanAmountData( $form ) {
			const result = {};
			let planAmount,
				setupFee,
				currency,
				currencySymbol,
				vatPercent,
				vatAmount,
				discountAmount;

			const $selectedPlan = findSelectedPlanNode( $form );
			if ( $selectedPlan && $selectedPlan.length == 1 ) {
				planAmount = $selectedPlan.data(
					'wpfs-plan-amount-in-smallest-common-currency'
				);
				setupFee = $selectedPlan.data(
					'wpfs-plan-setup-fee-in-smallest-common-currency'
				);
				currency = $selectedPlan.data( 'wpfs-currency' );
				currencySymbol = $selectedPlan.data( 'wpfs-currency-symbol' );
				vatPercent = 0;
			}
			if (
				planAmount === null ||
				isNaN( planAmount ) ||
				planAmount < 0
			) {
				result.valid = false;
			} else {
				const formId = $form.data( 'wpfs-form-id' );
				const coupon = WPFS.getCoupon( formId );
				const couponResult = applyCoupon(
					currency,
					planAmount + setupFee,
					coupon
				);
				discountAmount = couponResult.discount;
				const subTotal = planAmount + setupFee - discountAmount;
				vatAmount = calculateVATAmount( subTotal, vatPercent );
				result.valid = true;
				result.amount = subTotal + vatAmount;
				result.currency = currency;
				result.currencySymbol = currencySymbol;
			}
			return result;
		}

		var WPFS = {};
		WPFS.couponMap = {};
		WPFS.paymentDetailsMap = {};
		/**
		 * @param formId
		 * @param coupon
		 */
		WPFS.setCoupon = function ( formId, coupon ) {
			WPFS.couponMap[ formId ] = coupon;
		};
		/**
		 * @param  formId
		 * @return {*}
		 */
		WPFS.getCoupon = function ( formId ) {
			if ( WPFS.couponMap.hasOwnProperty( formId ) ) {
				return WPFS.couponMap[ formId ];
			}
			return null;
		};
		/**
		 * @param formId
		 */
		WPFS.removeCoupon = function ( formId ) {
			if ( WPFS.couponMap.hasOwnProperty( formId ) ) {
				delete WPFS.couponMap[ formId ];
			}
		};

		/**
		 * @param formId
		 * @param paymentDetails
		 */
		WPFS.setPaymentDetails = function ( formId, paymentDetails ) {
			WPFS.paymentDetailsMap[ formId ] = paymentDetails;
		};
		/**
		 * @param  formId
		 * @return {*}
		 */
		WPFS.getPaymentDetails = function ( formId ) {
			if ( WPFS.paymentDetailsMap.hasOwnProperty( formId ) ) {
				return WPFS.paymentDetailsMap[ formId ];
			}
			return null;
		};
		/**
		 * @param  formId
		 * @param  priceId
		 * @return {*}
		 */
		WPFS.getPaymentDetailsForPrice = function ( formId, priceId ) {
			const priceMap = WPFS.getPaymentDetails( formId );

			if ( priceMap !== null ) {
				if ( priceMap.hasOwnProperty( priceId ) ) {
					return priceMap[ priceId ];
				}
				return null;
			}
			return null;
		};
		/**
		 * @param formId
		 * @param priceId
		 * @param priceData
		 */
		WPFS.setPaymentDetailsForPrice = function (
			formId,
			priceId,
			priceData
		) {
			let priceMap = WPFS.getPaymentDetails( formId );
			if ( priceMap === null ) {
				priceMap = {};
			}

			priceMap[ priceId ] = priceData;
			WPFS.setPaymentDetails( formId, priceMap );
		};
		/**
		 * @param formId
		 */
		WPFS.removePaymentDetails = function ( formId ) {
			if ( WPFS.paymentDetailsMap.hasOwnProperty( formId ) ) {
				delete WPFS.paymentDetailsMap[ formId ];
			}
		};

		WPFS.initCustomAmount = function () {
			$( 'input.wpfs-custom-amount' ).change( function ( e ) {
				const $form = getParentForm( this );
				handleCustomAmountChange( $( this ), $form );
				handleAmountChange( $form );
			} );
			$( '.wpfs-custom-amount--unique' ).blur( function () {
				const $form = getParentForm( this );

				if ( isPossiblePricingRecalculationForm( $form ) ) {
					if (
						isNoTaxForm( $form ) &&
						isNoCouponAppliedForm( $form )
					) {
						refreshOtherPricingLocally( $form );
					} else {
						refreshPricingFromServer( $form );
					}
				} else {
					refreshPaymentDetails( $form );
				}
			} );
		};

		WPFS.initStripeJSCard = function () {
			const $cards = $(
				'form[data-wpfs-form-type] [data-toggle="card"]'
			);

			if ( $cards.length === 0 ) {
				return;
			}

			$cards.each( async function ( index ) {
				if ( stripe != null ) {
					var $form = getParentForm( this );
					const formId = $form.data( 'wpfs-form-id' );
					const elementsLocale = $form.data(
						'wpfs-preferred-language'
					);
					// Add get parameters to the form
					setPageParametersField( $form );

					// add amount index
					addCustomAmountIndexInput( $form );

					// this is used for both payment intent and setup intent secrets
					const { clientSecret, intentType } =
						await getSetupIntentClientSecret( $form );

					// set the intent type on the form so it can be used later
					$form.data( 'wpfs-intent-type', intentType );
					$form.data( 'wpfs-stripe-client-secret', clientSecret );
					$form.data(
						'wpfs-stripe-payment-intent-id',
						clientSecret.substr(
							0,
							clientSecret.indexOf( '_secret_' )
						)
					);

					let appearance = {
						theme: $form.data( 'wpfs-elements-theme' ),
					};

					if ( $form.data( 'wpfs-elements-font' ) ) {
						appearance = {
							...appearance,
							variables: {
								fontFamily: $form.data( 'wpfs-elements-font' ),
							},
						};
					}
					// create Stripe Payments Element
					var elements = stripe.elements( {
						locale: elementsLocale,
						loader: 'always',
						clientSecret,
						appearance,
					} );
					var cardElement = elements.create( 'payment', {
						fields: {
							billingDetails: {
								name: 'never', // never show the name field as it's handled by the FP form
								email: 'never', // never show the email field as it's handled by the FP form
							},
						},
					} );
					cardElement.mount(
						'div[data-wpfs-form-id="' + formId + '"]'
					);
				} else {
					console.error( 'Stripe.js not loaded' );
				}

				// handle form submission
				$form.submit( function ( event ) {
					event.preventDefault();
					/*
            disable submit button and show loading animation,
            clear message panel, reset token and amount index
          */
					disableFormButtons( $form );
					showLoadingAnimation( $form );
					clearFieldErrors( $form );
					clearGlobalMessage( $form );
					removeHiddenFormFields( $form );

					// Add get parameters to the form
					setPageParametersField( $form );

					// add amount index
					addCustomAmountIndexInput( $form );

					// validate terms of use if necessary
					const showTermsOfUse = $form.data(
						'wpfs-show-terms-of-use'
					);
					if ( 1 == showTermsOfUse ) {
						const termsOfUseAccepted = $form
							.find( 'input[name=wpfs-terms-of-use-accepted]' )
							.prop( 'checked' );
						if ( termsOfUseAccepted == false ) {
							const errorMessage = $form.data(
								'wpfs-terms-of-use-not-checked-error-message'
							);
							showFieldError(
								$form,
								'wpfs-terms-of-use-accepted',
								null,
								errorMessage
							);
							scrollToElement(
								$(
									'input[name=wpfs-terms-of-use-accepted]',
									$form
								),
								false
							);
							enableFormButtons( $form );
							hideLoadingAnimation( $form );
							return false;
						}
					}

					const paymentMethodData = {};

					// capture cardholder email
					const cardHolderEmail = $(
						'input[name="wpfs-card-holder-email"]',
						$form
					);
					if (
						cardHolderEmail &&
						cardHolderEmail.val() &&
						cardHolderEmail.val().length > 0
					) {
						paymentMethodData.billing_details = {
							email: cardHolderEmail.val(),
						};
					}

					// capture cardholder name
					const cardHolderNameField = $(
						'input[name="wpfs-card-holder-name"]',
						$form
					);
					if (
						cardHolderNameField &&
						cardHolderNameField.val() &&
						cardHolderNameField.val().length > 0
					) {
						if ( ! paymentMethodData.billing_details ) {
							paymentMethodData.billing_details = {};
						}
						paymentMethodData.billing_details.name =
							cardHolderNameField.val();
					}

					// capture billing address if country is set
					const billingAddressCountryField = $(
						'select[name="wpfs-billing-address-country"] option:selected',
						$form
					);
					if (
						billingAddressCountryField &&
						billingAddressCountryField.val() &&
						billingAddressCountryField.val().length > 0
					) {
						if ( ! paymentMethodData.billing_details ) {
							paymentMethodData.billing_details = {};
						}
						paymentMethodData.billing_details.address = {
							country: billingAddressCountryField.val(),
						};
						// get city if available
						const billingAddressCityField = $(
							'input[name="wpfs-billing-address-city"]',
							$form
						);
						if (
							billingAddressCityField &&
							billingAddressCityField.val() &&
							billingAddressCityField.val().length > 0
						) {
							paymentMethodData.billing_details.address.city =
								billingAddressCityField.val();
						}
						// get line 1
						const billingAddressLine1Field = $(
							'input[name="wpfs-billing-address-line-1"]',
							$form
						);
						if (
							billingAddressLine1Field &&
							billingAddressLine1Field.val() &&
							billingAddressLine1Field.val().length > 0
						) {
							paymentMethodData.billing_details.address.line1 =
								billingAddressLine1Field.val();
						}
						// get line 2
						const billingAddressLine2Field = $(
							'input[name="wpfs-billing-address-line-2"]',
							$form
						);
						if (
							billingAddressLine2Field &&
							billingAddressLine2Field.val() &&
							billingAddressLine2Field.val().length > 0
						) {
							paymentMethodData.billing_details.address.line2 =
								billingAddressLine2Field.val();
						}
						// get postal code
						const billingAddressPostalCodeField = $(
							'input[name="wpfs-billing-address-zip"]',
							$form
						);
						if (
							billingAddressPostalCodeField &&
							billingAddressPostalCodeField.val() &&
							billingAddressPostalCodeField.val().length > 0
						) {
							paymentMethodData.billing_details.address.postal_code =
								billingAddressPostalCodeField.val();
						}
						// get state
						const billingAddressStateField = $(
							'select[name="wpfs-billing-address-state-select"] option:selected',
							$form
						);
						if (
							billingAddressStateField &&
							billingAddressStateField.val() &&
							billingAddressStateField.val().length > 0
						) {
							paymentMethodData.billing_details.address.state =
								billingAddressStateField.val();
						}
					}

					if ( stripe != null ) {
						const intentType = $form.data( 'wpfs-intent-type' );
						const clientSecret = $form.data(
							'wpfs-stripe-client-secret'
						);
						let return_url = window.location.href;
						if ( $form.data( 'wpfs-form-id' ) ) {
							return_url += return_url.includes( '?' )
								? '&'
								: '?';
							return_url +=
								'wpfs-form-id=' + $form.data( 'wpfs-form-id' );
						}
						if ( intentType === 'payment' ) {
							// need to save a draft transaction locally before confirming the payment
							let data =
								createFormSerializer( $form ).serialize();
							data = JSON.parse(
								'{"' +
									data
										.replace( /&/g, '","' )
										.replace( /=/g, '":"' ) +
									'"}',
								function ( key, value ) {
									return key === ''
										? value
										: decodeURIComponent( value );
								}
							);
							data.action = 'wpfs-save-draft-transaction';
							// custom fields are not serialized correct by default
							delete data[ 'wpfs-custom-input%5B%5D' ];
							const inputFields = $(
								'input[name="wpfs-custom-input[]"]',
								$form
							);
							const customInputValues = [];
							inputFields.each( function ( index, element ) {
								const $element = $( element );
								customInputValues.push( $element.val() );
							} );
							data[ 'wpfs-custom-input[]' ] = customInputValues;
							data[ 'wpfs-intent-type' ] = intentType;
							data[ 'wpfs-stripe-client-secret' ] = clientSecret;
							data[ 'wpfs-stripe-payment-intent-id' ] =
								clientSecret.substr(
									0,
									clientSecret.indexOf( '_secret_' )
								);

							$.ajax( {
								type: 'POST',
								url: wpfsFormSettings.ajaxUrl,
								data,
								cache: false,
								dataType: 'json',
								success( data ) {
									// now confirm the payment and wait for the redirect or the webhook
									if (
										data.success &&
										data.success === true
									) {
										stripe
											.confirmPayment( {
												elements,
												confirmParams: {
													return_url,
													payment_method_data:
														paymentMethodData,
												},
											} )
											.catch( ( error ) => {
												enableFormButtons( $form );
												hideLoadingAnimation( $form );
												showErrorGlobalMessage(
													$form,
													wpfsFormSettings.l10n
														.stripe_errors
														.internal_error,
													error.message
												);
												console.log( error );
											} );
									} else {
										if (
											data &&
											( data.messageTitle ||
												data.message )
										) {
											showErrorGlobalMessage(
												$form,
												data.messageTitle,
												data.message
											);
										}
										processValidationErrors( $form, data );
										enableFormButtons( $form );
										hideLoadingAnimation( $form );
									}
								},
								error( jqXHR, textStatus, errorThrown ) {
									logError(
										'submitDraftTransation',
										jqXHR,
										textStatus,
										errorThrown
									);
									showErrorGlobalMessage(
										$form,
										wpfsFormSettings.l10n.stripe_errors
											.internal_error
									);
									enableFormButtons( $form );
									hideLoadingAnimation( $form );
									console.log( errorThrown );
								},
							} );
						} else if ( intentType === 'setup' ) {
							stripe
								.confirmSetup({
									elements,
									confirmParams: {
										return_url: return_url,
										payment_method_data: paymentMethodData
									},
									redirect: 'if_required'
								})
								.then( function ( createPaymentMethodResult ) {
									if ( debugLog ) {
										console.log(
											'form.submit(): ' +
												'PaymentMethod creation result=' +
												JSON.stringify(
													createPaymentMethodResult
												)
										);
									}
									clearFieldErrors( $form );
									if ( createPaymentMethodResult.error ) {
										enableFormButtons( $form );
										hideLoadingAnimation( $form );
										showFieldError(
											$form,
											'cardnumber',
											null,
											createPaymentMethodResult.error
												.message
										);
										scrollToElement(
											$( '.wpfs-form-card', $form ),
											false
										);
									} else {
										addPaymentMethodIdInput(
											$form,
											createPaymentMethodResult
										);
										submitPaymentData( $form, cardElement );
									}
								} )
								.catch( ( error ) => {
									enableFormButtons( $form );
									hideLoadingAnimation( $form );
									showErrorGlobalMessage(
										$form,
										wpfsFormSettings.l10n.stripe_errors
											.internal_error,
										error.message
									);
									console.log( error );
								} );
						}
					} else {
						console.error( '[Sumbit] Stripe.js not loaded' );
					}

					return false;
				} );
			} );
		};

		WPFS.initCoupon = function () {
			const COUPON_FIELD_NAME = 'wpfs-coupon';

			function getCouponNode( $form ) {
				return $( 'input[name="' + COUPON_FIELD_NAME + '"]', $form );
			}

			function emptyCouponField( $form ) {
				const formId = extractFormNameFromNode( $form );
				WPFS.removeCoupon( formId );

				const $coupon = getCouponNode( $form );
				if ( $coupon && $coupon.length > 0 ) {
					$coupon.val( '' );
				}
			}

			$( '.wpfs-coupon-remove-link' ).click( function ( event ) {
				event.preventDefault();
				const $form = getParentForm( this );

				emptyCouponField( $form );

				hideRedeemedCouponRow( $form );
				hideWrongRedeemedCouponRow( $form );
				showCouponToRedeemRow( $form );

				refreshPricingFromServer( $form );
			} );
			$( '.wpfs-coupon-add-another-link' ).click( function ( event ) {
				event.preventDefault();
				const $form = getParentForm( this );
				const $coupon = getCouponNode( $form );
				const couponFieldId = $coupon.attr( 'id' );

				emptyCouponField( $form );
				clearFieldError( $form, COUPON_FIELD_NAME, couponFieldId );

				hideRedeemedCouponRow( $form );
				hideWrongRedeemedCouponRow( $form );
				showCouponToRedeemRow( $form );

				refreshPricingFromServer( $form );
			} );
			$( '.wpfs-coupon-redeem-link' ).click( function ( event ) {
				event.preventDefault();
				const $form = getParentForm( this );
				const $coupon = getCouponNode( $form );

				const taxData = gatherFormDataForTaxCalculation( $form );
				taxData.coupon = $coupon.val();
				// include payment intent id if available
				const intentType = $form.data( 'wpfs-intent-type' );
				if ( intentType && intentType === 'payment' ) {
					taxData.stripePaymentIntentId = $form.data(
						'wpfs-stripe-payment-intent-id'
					);
				}

				disableFormButtons( $form );
				if ( $coupon && $coupon.length > 0 ) {
					clearGlobalMessage( $form );
					clearFieldErrors( $form );
					$coupon.prop( 'disabled', true );
					showRedeemLoadingAnimation( $form );

					$.ajax( {
						type: 'POST',
						url: wpfsFormSettings.ajaxUrl,
						data: {
							action: 'wpfs-check-coupon',
							code: $coupon.val(),
							taxData,
						},
						cache: false,
						dataType: 'json',
						success( couponRedeemData ) {
							if ( couponRedeemData.success ) {
								WPFS.setCoupon(
									extractFormNameFromNode( $form ),
									couponRedeemData.coupon
								);

								setPaymentDetailsData(
									$form,
									couponRedeemData.productPricing
								);
								refreshProductPricing( $form );
								refreshPaymentDetails( $form );
							} else if ( couponRedeemData.bindingResult ) {
								processValidationErrors(
									$form,
									couponRedeemData
								);
							} else {
								console.log(
									'Calling the coupon error field directly...'
								);
								const couponFieldId = $coupon.attr( 'id' );
								showFieldError(
									$form,
									COUPON_FIELD_NAME,
									couponFieldId,
									couponRedeemData.msg
								);
								scrollToElement( $coupon, false );
							}
						},
						error( jqXHR, textStatus, errorThrown ) {
							logError(
								'coupon',
								jqXHR,
								textStatus,
								errorThrown
							);
							showErrorGlobalMessage(
								$form,
								wpfsFormSettings.l10n.stripe_errors
									.internal_error
							);
						},
						complete() {
							$coupon.prop( 'disabled', false );
							hideRedeemLoadingAnimation( $form );
							enableFormButtons( $form );
						},
					} );
				}
			} );
		};

		WPFS.initInputGroup = function () {
			const inputGroupPrependClass = '.wpfs-input-group-prepend';
			$( document ).on( 'click', inputGroupPrependClass, function ( e ) {
				const $target = $( e.target );
				if ( $target.hasClass( 'wpfs-input-group-link' ) ) {
					return;
				}

				if (
					$target.parents( '.wpfs-input-group-link' ) &&
					$target.parents( '.wpfs-input-group-link' ).length > 0
				) {
					return;
				}

				$( this ).next().focus();
			} );

			$( document ).on(
				'mouseenter',
				inputGroupPrependClass,
				function () {
					$( this ).next().mouseenter();
				}
			);

			$( document ).on(
				'mouseleave',
				inputGroupPrependClass,
				function () {
					$( this ).next().mouseleave();
				}
			);

			const inputGroupAppendClass = '.wpfs-input-group-append';
			$( document ).on( 'click', inputGroupAppendClass, function ( e ) {
				const $target = $( e.target );
				if ( $target.hasClass( 'wpfs-input-group-link' ) ) {
					return;
				}

				if (
					$target.parents( '.wpfs-input-group-link' ) &&
					$target.parents( '.wpfs-input-group-link' ).length > 0
				) {
					return;
				}

				$( this ).prev().focus();
			} );

			$( document ).on( 'mouseenter', inputGroupAppendClass, function () {
				$( this ).prev().mouseenter();
			} );

			$( document ).on( 'mouseleave', inputGroupAppendClass, function () {
				$( this ).prev().mouseleave();
			} );
		};

		WPFS.initSelectmenu = function () {
			$.widget( 'custom.wpfsSelectmenu', $.ui.selectmenu, {
				_renderItem( ul, item ) {
					const $li = $( '<li>' );
					const wrapper = $( '<div>', {
						class: 'menu-item-wrapper ui-menu-item-wrapper',
						text: item.label,
					} );

					if ( item.disabled ) {
						$li.addClass( 'ui-state-disabled' );
					}

					return $li.append( wrapper ).appendTo( ul );
				},
			} );

			const $selectmenus = $( '[data-toggle="selectmenu"]' );
			$selectmenus.each( function () {
				if ( typeof $( this ).select2 === 'function' ) {
					try {
						$( this ).select2( 'destroy' );
					} catch ( err ) {}
				}

				const $selectmenu = $( this ).wpfsSelectmenu( {
					classes: {
						'ui-selectmenu-button':
							'wpfs-form-control wpfs-selectmenu-button',
						'ui-selectmenu-menu': 'wpfs-ui wpfs-selectmenu-menu',
					},
					icons: {
						button: 'wpfs-icon-arrow',
					},
					create() {
						if ( debugLog ) {
							console.log( 'selectmenu.create(): CALLED' );
						}
						const $this = $( this );
						const $selectMenuButton = $this.next();
						$selectMenuButton.addClass( $this.attr( 'class' ) );
						if (
							$this.find( 'option:selected:disabled' ) &&
							$this.find( 'option:selected:disabled' ).length > 0
						) {
							$selectMenuButton.addClass(
								'ui-state-placeholder'
							);
						}
					},
					open() {
						if ( debugLog ) {
							console.log( 'selectmenu.open(): CALLED' );
						}
						const $this = $( this );
						const $button = $this.data(
							'custom-wpfsSelectmenu'
						).button;
						$button.removeClass( 'ui-selectmenu-button-closed' );
						$button.addClass( 'ui-selectmenu-button-open' );
						const selectedClass = 'ui-state-selected';
						const selectedIndex = $this
							.find( 'option' )
							.index( $this.find( 'option:selected' ) );
						$(
							'.ui-selectmenu-open .ui-menu-item-wrapper'
						).removeClass( selectedClass );
						const $menuItem = $(
							'.ui-selectmenu-open .ui-menu-item'
						).eq( selectedIndex );
						if ( ! $menuItem.hasClass( 'ui-state-disabled' ) ) {
							$menuItem
								.find( '.ui-menu-item-wrapper' )
								.addClass( selectedClass );
						}
					},
					close() {
						if ( debugLog ) {
							console.log( 'selectmenu.close(): CALLED' );
						}
						const $this = $( this );
						const $button = $this.data(
							'custom-wpfsSelectmenu'
						).button;
						$button.removeClass( 'ui-selectmenu-button-open' );
						$button.addClass( 'ui-selectmenu-button-closed' );
					},
					change( event, ui ) {
						if ( debugLog ) {
							console.log( 'selectmenu.change(): CALLED' );
						}
						// todo: Remove harcoded event handling, put it in event handlers
						const $selectElement = $( event.target );
						const $form = getParentForm( event.target );
						if ( $selectElement && $selectElement.length > 0 ) {
							const selectElementType =
								$selectElement.data( 'wpfs-select' );
							if (
								'wpfs-billing-address-country-select' ===
								selectElementType
							) {
								const $billingAddressCountrySelectElement = $(
									'.wpfs-billing-address-country-select',
									$form
								);
								handleBillingAddressCountryChange(
									$billingAddressCountrySelectElement,
									$form
								);
							} else if (
								'wpfs-billing-address-state-select' ===
								selectElementType
							) {
								const $billingAddressStateSelectElement = $(
									'.wpfs-billing-address-state-select',
									$form
								);
								handleBillingAddressStateChange(
									$billingAddressStateSelectElement,
									$form
								);
							} else if (
								'wpfs-shipping-address-country-select' ===
								selectElementType
							) {
								const $shippingAddressCountrySelectElement = $(
									'.wpfs-shipping-address-country-select',
									$form
								);
								handleShippingAddressCountryChange(
									$shippingAddressCountrySelectElement,
									$form
								);
							} else if (
								'wpfs-custom-amount-select' ===
								selectElementType
							) {
								handleAmountChange( $form );
								const amountType =
									$form.data( 'wpfs-amount-type' );
								if ( 'list_of_amounts' === amountType ) {
									const $customAmountSelectElement = $(
										'.wpfs-custom-amount-select',
										$form
									);
									handleCustomAmountChange(
										$customAmountSelectElement,
										$form
									);
								}
							} else if (
								'wpfs-subscription-plan-select' ===
								selectElementType
							) {
								handlePlanChange( $form );
							} else if (
								'wpfs-tax-country-select' === selectElementType
							) {
								//handleTaxCountryChange($form);
							} else if (
								'wpfs-tax-state-select' === selectElementType
							) {
								handleTaxStateChange( $form );
							}
						}
						$( this ).next().removeClass( 'ui-state-placeholder' );
						$( this ).trigger( 'selectmenuchange' );
					},
				} );

				const $selectmenuParent = $selectmenu.parent();
				$selectmenuParent
					.find( '.ui-selectmenu-button' )
					.addClass( 'wpfs-form-control' )
					.addClass( 'wpfs-selectmenu-button' )
					.addClass( 'ui-button' );

				$selectmenu
					.data( 'custom-wpfsSelectmenu' )
					.menuWrap.addClass( 'wpfs-ui' )
					.addClass( 'wpfs-selectmenu-menu' );
			} );
		};

		WPFS.initAddressSwitcher = function () {
			$( '.wpfs-same-billing-and-shipping-address' ).change( function () {
				if ( debugLog ) {
					logInfo(
						'.wpfs-same-billing-and-shipping-address CHANGE',
						'CALLED'
					);
				}
				const addressSwitcherId = $( this ).data(
					'wpfs-address-switcher-id'
				);
				const billingAddressSwitchId = $( this ).data(
					'wpfs-billing-address-switch-id'
				);
				if ( $( this ).prop( 'checked' ) ) {
					$( '#' + addressSwitcherId ).hide();
					$( '#' + billingAddressSwitchId ).click();
				} else {
					$( '#' + addressSwitcherId ).show();
					$( '#' + billingAddressSwitchId ).click();
				}
				$( '.wpfs-billing-address-switch' ).change();
				$( '.wpfs-shipping-address-switch' ).change();
			} );
			$( '.wpfs-billing-address-switch' ).change( function () {
				if ( debugLog ) {
					logInfo( '.wpfs-billing-address-switch CHANGE', 'CALLED' );
				}
				const billingAddressPanelId = $( this ).data(
					'wpfs-billing-address-panel-id'
				);
				const shippingAddressPanelId = $( this ).data(
					'wpfs-shipping-address-panel-id'
				);
				if ( $( this ).prop( 'checked' ) ) {
					$( '#' + billingAddressPanelId ).show();
					$( '#' + shippingAddressPanelId ).hide();
				}
			} );
			$( '.wpfs-shipping-address-switch' ).change( function () {
				const billingAddressPanelId = $( this ).data(
					'wpfs-billing-address-panel-id'
				);
				const shippingAddressPanelId = $( this ).data(
					'wpfs-shipping-address-panel-id'
				);
				if ( $( this ).prop( 'checked' ) ) {
					$( '#' + billingAddressPanelId ).hide();
					$( '#' + shippingAddressPanelId ).show();
				}
			} );
		};

		WPFS.initTooltip = function () {
			$.widget.bridge( 'wpfstooltip', $.ui.tooltip );

			$( '[data-toggle="tooltip"]' )
				.wpfstooltip( {
					items: '[data-toggle="tooltip"]',
					content() {
						const contentId = $( this ).data( 'tooltip-content' );
						return $(
							'[data-tooltip-id="' + contentId + '"]'
						).html();
					},
					position: {
						my: 'left top+5',
						at: 'left bottom+5',
						using( position, feedback ) {
							const $this = $( this );
							$this.css( position );
							$this
								.addClass( feedback.vertical )
								.addClass( feedback.horizontal );
						},
					},
					classes: {
						'ui-tooltip': 'wpfs-ui wpfs-tooltip',
					},
					tooltipClass: 'wpfs-ui wpfs-tooltip',
				} )
				.on( {
					click( event ) {
						event.preventDefault();
						$( this ).tooltip( 'open' );
					},
				} );
		};

		WPFS.initStepper = function () {
			const $stepper = $( '[data-toggle="stepper"]' );
			$stepper.each( function () {
				const $this = $( this );
				let defaultValue = $this.data( 'defaultValue' ) || 1;

				if ( $this.val() === '' ) {
					$this.val( defaultValue );
				}

				$this
					.spinner( {
						min: $this.data( 'min' ) || 1,
						max: $this.data( 'max' ) || 9999,
						icons: {
							down: 'wpfs-icon-decrease',
							up: 'wpfs-icon-increase',
						},
						change( e, ui ) {
							const $this = $( this );
							if ( $this.spinner( 'isValid' ) ) {
								defaultValue = $this.val();
							} else {
								$this.val( defaultValue );
							}
						},
						spin( e, ui ) {
							const $this = $( this );
							const uiSpinner = $this.data( 'uiSpinner' );
							const min = uiSpinner.options.min;
							const max = uiSpinner.options.max;
							const $container = $this.parent();
							const disabledClassName = 'ui-state-disabled';
							const up = $container.find( '.ui-spinner-up' );
							const down = $container.find( '.ui-spinner-down' );

							up.removeClass( disabledClassName );
							down.removeClass( disabledClassName );

							if ( ui.value === max ) {
								up.addClass( disabledClassName );
							}

							if ( ui.value === min ) {
								down.addClass( disabledClassName );
							}
						},
					} )
					.parent()
					.find( '.ui-icon' )
					.text( '' );
			} );
		};

		WPFS.initDatepicker = function () {
			$( '[data-toggle="datepicker"]' ).each( function () {
				const $this = $( this );
				const dateFormat = $this.data( 'dateFormat' ) || 'dd/mm/yyyy';
				let defaultValue = $this.data( 'defaultValue' ) || '';

				if ( $this.val() === '' ) {
					$this.val( defaultValue );
				}

				$this
					.attr( 'placeholder', dateFormat )
					.inputmask( {
						alias: dateFormat,
						showMaskOnHover: false,
						showMaskOnFocus: false,
					} )
					.datepicker( {
						prevText: '',
						nextText: '',
						hideIfNoPrevNext: true,
						firstDay: 1,
						dateFormat: dateFormat.replace( /yy/g, 'y' ),
						showOtherMonths: true,
						selectOtherMonths: true,
						onChangeMonthYear( year, month, inst ) {
							if ( inst.dpDiv.hasClass( 'bottom' ) ) {
								setTimeout( function () {
									inst.dpDiv.css(
										'top',
										inst.input.offset().top -
											inst.dpDiv.outerHeight()
									);
								} );
							}
						},
						beforeShow( el, inst ) {
							const $el = $( el );
							inst.dpDiv.addClass(
								'wpfs-ui wpfs-datepicker-div'
							);
							setTimeout( function () {
								if (
									$el.offset().top > inst.dpDiv.offset().top
								) {
									inst.dpDiv.removeClass( 'top' );
									inst.dpDiv.addClass( 'bottom' );
								} else {
									inst.dpDiv.removeClass( 'bottom' );
									inst.dpDiv.addClass( 'top' );
								}
							} );
						},
					} )
					.on( 'blur', function () {
						const $this = $( this );
						setTimeout( function () {
							const date = $this.val();
							const isValid = Inputmask.isValid( date, {
								alias: dateFormat,
							} );
							if ( isValid ) {
								defaultValue = date;
							} else {
								$this.val( defaultValue );
							}
						}, 50 );
					} );
			} );
		};

		WPFS.initCombobox = function () {
			$.widget( 'custom.combobox', {
				_selectOptions: [],
				_lastValidValue: null,
				_create() {
					this.wrapper = $( '<div>' )
						.addClass( 'wpfs-input-group wpfs-combobox' )
						.addClass( this.element.attr( 'class' ) )
						.insertAfter( this.element );

					this._selectOptions = this.element
						.children( 'option' )
						.map( function () {
							const $this = $( this );
							const text = $this.text();
							const value = $this.val();
							if ( value && value !== '' ) {
								return {
									label: text,
									value: text,
									option: this,
								};
							}
						} );

					this.element.hide();
					this._createAutocomplete();
					this._createShowAllButton();
				},
				_createAutocomplete() {
					const selected = this.element.children( ':selected' );
					const value = selected.val() ? selected.text() : '';

					this.input = $( '<input>' )
						.attr(
							'placeholder',
							this.element.data( 'placeholder' )
						)
						.appendTo( this.wrapper )
						.val( value )
						.addClass( 'wpfs-input-group-form-control' )
						.autocomplete( {
							delay: 0,
							minLength: 0,
							source: $.proxy( this, '_source' ),
							position: {
								my: 'left-1px top+2px',
								at: 'left-1px bottom+2px',
								using( position, feedback ) {
									const $this = $( this );
									$this.css( position );
									$this.width( feedback.target.width + 46 );
								},
							},
							classes: {
								'ui-autocomplete': 'wpfs-ui wpfs-combobox-menu',
							},
							open() {
								$( this )
									.parent()
									.addClass( 'wpfs-combobox--open' );
							},
							close() {
								const $this = $( this );
								$this
									.parent()
									.removeClass( 'wpfs-combobox--open' );
								$this.blur();
							},
							search( e, ui ) {
								// Fix autocomplete combobox memory leak
								$( this ).data(
									'uiAutocomplete'
								).menu.bindings = $();
							},
						} )
						.on( 'focus', function () {
							$( this ).data( 'uiAutocomplete' ).search( '' );
						} )
						.on( 'keydown', function ( e ) {
							if ( e.keyCode === 13 ) {
								$( this ).blur();
							}
						} );

					this.input.data( 'uiAutocomplete' )._renderItem =
						this._renderItem;

					this._on( this.input, {
						autocompleteselect( e, ui ) {
							if ( ! ui.item.noResultsItem ) {
								ui.item.option.selected = true;
								this._trigger( 'select', e, {
									item: ui.item.option,
								} );
							}
							this._trigger( 'blur' );
						},
						autocompletechange: '_validateValue',
					} );
				},
				_createShowAllButton() {
					const input = this.input;
					let wasOpen = false;
					const html =
						'<div class="wpfs-input-group-append"><span class="wpfs-input-group-icon"><span class="wpfs-icon-arrow"></span></span></div>';

					$( html )
						.appendTo( this.wrapper )
						.on( 'mousedown', function () {
							wasOpen = input
								.autocomplete( 'widget' )
								.is( ':visible' );
						} )
						.on( 'click', function ( e ) {
							e.stopPropagation();
							if ( wasOpen ) {
								input.autocomplete( 'close' );
							} else {
								input.trigger( 'focus' );
							}
						} );
				},
				_source( request, response ) {
					const matcher = new RegExp(
						$.ui.autocomplete.escapeRegex( request.term ),
						'i'
					);
					const results = this._selectOptions.map(
						function ( i, option ) {
							if (
								option.value &&
								( ! request.term ||
									matcher.test( option.label ) )
							) {
								return option;
							}
						}
					);

					if ( results && results.length > 0 ) {
						response( results );
					} else {
						response( [
							{
								label: this.element.data( 'noResultsMessage' ),
								value: request.term,
								noResultsItem: true,
							},
						] );
					}
				},
				_validateValue( e, ui ) {
					// Selected an item, nothing to do
					if ( ui.item ) {
						return;
					}

					// Search for a match (case-insensitive)
					const value = this.input.val();
					const valueLowerCase = value.toLowerCase();
					let valid = false;
					let selectedText = null;
					this.element.children( 'option' ).each( function () {
						const text = $( this ).text();
						if ( text.toLowerCase() === valueLowerCase ) {
							selectedText = text;
							this.selected = valid = true;
							return false;
						}
					} );

					if ( valid ) {
						// Fix valid value
						this.input.val( selectedText );
						this._lastValidValue = selectedText;
					} else if ( ! valid && this._lastValidValue !== null ) {
						// Set last valid value
						this.input.val( this._lastValidValue );
					} else {
						// Remove invalid value
						this.input.val( '' );
						this.element.val( '' );
						this.input.autocomplete( 'instance' ).term = '';
					}
				},
				_renderItem( ul, item ) {
					let t = '';
					const idx = item.label
						.toLowerCase()
						.indexOf( this.term.toLowerCase() );
					const sameLabelAndTerm =
						item.label.toLowerCase() === this.term.toLowerCase();

					if (
						idx !== -1 &&
						! sameLabelAndTerm &&
						this.term !== ''
					) {
						const termLength = this.term.length;
						t += item.label.substring( 0, idx );
						t +=
							'<strong>' +
							item.label.substr( idx, termLength ) +
							'</strong>';
						t += item.label.substr( idx + termLength );
					} else {
						t = item.label;
					}

					const className = '';
					const $li = $( '<li></li>' );
					const $div = $(
						'<div class="ui-menu-item-wrapper">' + t + '</div>'
					);
					if ( ! item.noResultsItem ) {
						$li.data( 'item.autocomplete', item );
						if ( sameLabelAndTerm ) {
							$div.addClass( 'ui-state-selected' );
						}
					} else {
						$li.addClass( 'ui-state-disabled' );
					}

					ul.addClass( 'wpfs-ui' ).addClass( 'wpfs-combobox-menu' );

					return $li.append( $div ).appendTo( ul );
				},
				_destroy() {
					this.wrapper.remove();
					this.element.show();
				},
			} );

			$( '[data-toggle="combobox"]' ).combobox();
		};

		WPFS.initInlineForms = function () {
			const inlineFormSelector =
				'form[data-wpfs-form-type="' +
				FORM_TYPE_INLINE_PAYMENT +
				'"], form[data-wpfs-form-type="' +
				FORM_TYPE_INLINE_SUBSCRIPTION +
				'"], form[data-wpfs-form-type="' +
				FORM_TYPE_INLINE_SAVE_CARD +
				'"], form[data-wpfs-form-type="' +
				FORM_TYPE_INLINE_DONATION +
				'"]';
			const $inlineForms = $( inlineFormSelector );
			$inlineForms.each( function ( index, inlineForm ) {
				const $form = $( inlineForm );

				if ( isDynamicTaxesForm( $form ) ) {
					handleTaxStateVisibility( $form );
				} else if ( isStripeTaxForm( $form ) ) {
					refreshTaxPostalCodeVisibility( $form );
				}

				if ( isTaxIdCollectible( $form ) ) {
					refreshTaxIdPlaceholder( $form );
				}
			} );
		};

		WPFS.initCheckoutForms = function () {
			if ( debugLog ) {
				logInfo( 'initCheckoutForms', 'CALLED' );
			}

			const popupFormsSelector =
				'form[data-wpfs-form-type="' +
				FORM_TYPE_CHECKOUT_PAYMENT +
				'"], form[data-wpfs-form-type="' +
				FORM_TYPE_CHECKOUT_SUBSCRIPTION +
				'"], form[data-wpfs-form-type="' +
				FORM_TYPE_CHECKOUT_SAVE_CARD +
				'"], form[data-wpfs-form-type="' +
				FORM_TYPE_CHECKOUT_DONATION +
				'"]';
			const $popupForms = $( popupFormsSelector );
			$popupForms.each( function ( index, popupForm ) {
				const $form = $( popupForm );

				if ( debugLog ) {
					logInfo(
						'initCheckoutForms',
						'form=' + $form.data( 'wpfs-form-id' )
					);
				}

				$form.submit( function ( event ) {
					if ( debugLog ) {
						logInfo(
							'checkoutFormSubmit',
							'CALLED, formId=' + $form.data( 'wpfs-form-id' )
						);
					}

					event.preventDefault();

					/*
                         tnagy disable submit button and show loading animation,
                         clear message panel, reset token and amount index
                         */
					disableFormButtons( $form );
					showLoadingAnimation( $form );
					clearFieldErrors( $form );
					clearGlobalMessage( $form );
					removeCustomAmountIndexInput( $form );

					// Add get parameters to the form
					setPageParametersField( $form );

					// tnagy add amount index
					addCustomAmountIndexInput( $form );

					// tnagy validate form
					let hasErrors = false;
					let $firstInvalidField = null;
					let fieldErrorMessage = null;

					// tnagy validate custom fields
					const customInputRequired = $form.data(
						'wpfs-custom-input-required'
					);
					if ( 1 == customInputRequired ) {
						const customInputFieldsWithMissingValue = [];
						let customInputValues = $(
							'input[name="wpfs-custom-input"]',
							$form
						);
						if ( customInputValues.length == 0 ) {
							customInputValues = $(
								'input[name="wpfs-custom-input[]"]',
								$form
							);
						}
						if ( customInputValues ) {
							customInputValues.each( function () {
								if ( $( this ).val().length == 0 ) {
									customInputFieldsWithMissingValue.push(
										this
									);
								}
							} );
						}
						if (
							customInputFieldsWithMissingValue &&
							customInputFieldsWithMissingValue.length > 0
						) {
							for ( const index in customInputFieldsWithMissingValue ) {
								const $customInputField = $(
									customInputFieldsWithMissingValue[ index ]
								);
								if (
									$customInputField &&
									$customInputField.length > 0
								) {
									if ( $firstInvalidField == null ) {
										$firstInvalidField = $customInputField;
									}
									const id = $customInputField.attr( 'id' );
									let name = $customInputField.attr( 'name' );
									if ( name ) {
										name = name.replace( /\[]/g, '' );
									}
									const label = $customInputField.data(
										'wpfs-custom-input-label'
									);
									fieldErrorMessage = vsprintf(
										wpfsFormSettings.l10n.validation_errors
											.mandatory_field_is_empty,
										[ label ]
									);
									showFieldError(
										$form,
										name,
										id,
										fieldErrorMessage,
										false
									);
								}
							}
							hasErrors = true;
						}
					}

					// tnagy validate terms of use if necessary
					const showTermsOfUse = $form.data(
						'wpfs-show-terms-of-use'
					);
					if ( 1 == showTermsOfUse ) {
						const termsOfUseAccepted = $form
							.find( 'input[name=wpfs-terms-of-use-accepted]' )
							.prop( 'checked' );
						if ( termsOfUseAccepted == false ) {
							if ( $firstInvalidField == null ) {
								$firstInvalidField = $(
									'input[name=wpfs-terms-of-use-accepted]',
									$form
								);
							}
							fieldErrorMessage = $form.data(
								'wpfs-terms-of-use-not-checked-error-message'
							);
							showFieldError(
								$form,
								'wpfs-terms-of-use-accepted',
								null,
								fieldErrorMessage,
								false
							);
							hasErrors = true;
						}
					}

					// tnagy prevent submit on validation errors
					if ( hasErrors ) {
						if (
							$firstInvalidField &&
							$firstInvalidField.length > 0
						) {
							scrollToElement( $firstInvalidField, false );
						}
						enableFormButtons( $form );
						hideLoadingAnimation( $form );
						return false;
					}

					// tnagy continue with valid data
					const formType = $form.data( 'wpfs-form-type' );
					let amountData;
					if (
						FORM_TYPE_CHECKOUT_PAYMENT === formType ||
						FORM_TYPE_CHECKOUT_DONATION === formType
					) {
						amountData = findPaymentAmountData( $form );
					} else if ( FORM_TYPE_CHECKOUT_SUBSCRIPTION === formType ) {
						amountData = findPlanAmountData( $form );
					} else if ( FORM_TYPE_CHECKOUT_SAVE_CARD === formType ) {
						amountData = {
							paymentType: PAYMENT_TYPE_CARD_CAPTURE,
							customAmount: false,
							currency: 'usd',
							amount: 0,
							valid: true,
						};
					}

					if ( debugLog ) {
						logInfo(
							'initCheckoutForms',
							'amountData=' + JSON.stringify( amountData )
						);
					}

					if ( false == amountData.valid ) {
						if ( amountData.customAmount ) {
							showFieldError(
								$form,
								'wpfs-custom-amount-unique',
								$(
									'input[name="wpfs-custom-amount-unique"]',
									$form
								).attr( 'id' ),
								wpfsFormSettings.l10n.validation_errors
									.custom_payment_amount_value_is_invalid,
								true
							);
						} else {
							showErrorGlobalMessage(
								$form,
								wpfsFormSettings.l10n.validation_errors
									.invalid_payment_amount_title,
								wpfsFormSettings.l10n.validation_errors
									.invalid_payment_amount
							);
						}
						enableFormButtons( $form );
						hideLoadingAnimation( $form );
						return false;
					}

					addCurrentURL( $form );

					if ( stripe != null ) {
						$.ajax( {
							type: 'POST',
							url: wpfsFormSettings.ajaxUrl,
							data: $form.serialize(),
							cache: false,
							dataType: 'json',
							success( data ) {
								if ( debugLog ) {
									logInfo(
										'initCheckoutForms',
										'SUCCESS response=' +
											JSON.stringify( data )
									);
								}

								if ( data.success ) {
									if ( data.redirectUrl ) {
										location.href = data.redirectUrl;
									}
								} else {
									if (
										data &&
										( data.messageTitle || data.message )
									) {
										showErrorGlobalMessage(
											$form,
											data.messageTitle,
											data.message
										);
									}
									processValidationErrors( $form, data );
								}
							},
							error( jqXHR, textStatus, errorThrown ) {
								logError(
									'initCheckoutForms',
									jqXHR,
									textStatus,
									errorThrown
								);
								showErrorGlobalMessage(
									$form,
									wpfsFormSettings.l10n.validation_errors
										.internal_error_title,
									wpfsFormSettings.l10n.validation_errors
										.internal_error
								);
							},
							complete() {
								enableFormButtons( $form );
								hideLoadingAnimation( $form );
							},
						} );
					} else {
						// todo tnagy show error message
					}

					return false;
				} );
			} );
		};

		WPFS.initReCaptcha = function () {
			window.addEventListener(
				'load',
				function () {
					let reCAPTCHAWidgetId;
					let formHash;
					const formCAPTCHAs =
						document.getElementsByClassName( 'wpfs-form-captcha' );
					//noinspection JSUnresolvedVariable
					if (
						window.grecaptcha !== 'undefined' &&
						formCAPTCHAs !== null &&
						formCAPTCHAs.length > 0
					) {
						//noinspection JSUnresolvedVariable
						googleReCAPTCHA = window.grecaptcha;
						Array.prototype.forEach.call(
							formCAPTCHAs,
							function ( element ) {
								formHash = element.getAttribute(
									'data-wpfs-form-hash'
								);
								//noinspection JSUnresolvedVariable
								reCAPTCHAWidgetId = googleReCAPTCHA.render(
									element,
									{
										sitekey:
											wpfsFormSettings.googleReCaptchaSiteKey,
									}
								);
								reCAPTCHAWidgetIds[ formHash ] =
									reCAPTCHAWidgetId;
							}
						);
					}
				},
				true
			);
		};

		WPFS.initPlanRadioButton = function () {
			$( 'input.wpfs-subscription-plan-radio' ).change( function ( e ) {
				const $form = getParentForm( this );
				handlePlanChange( $form );
			} );
		};

		WPFS.initTaxFieldChanges = function () {
			$( 'select.wpfs-tax-country-select' ).on(
				'selectmenuchange',
				function ( e ) {
					const $form = getParentForm( this );
					handleTaxCountryChange( $form );
				}
			);

			$( 'select.wpfs-tax-id-type-select' ).on(
				'selectmenuchange',
				function ( e ) {
					const $form = getParentForm( this );
					handleTaxIdTypeChange( $form );
				}
			);

			$( 'input[name="wpfs-tax-zip"]' ).change( function ( e ) {
				const $form = getParentForm( this );

				refreshPricingFromServer( $form );
			} );

			$( 'input[name="wpfs-billing-address-zip"]' ).change(
				function ( e ) {
					const $form = getParentForm( this );

					if ( isStripeTaxForm( $form ) ) {
						refreshPricingFromServer( $form );
					}
				}
			);
		};

		WPFS.initBuyingAsBusiness = function () {
			$( 'input[name="wpfs-buying-as-business"]' ).change(
				function ( e ) {
					const $form = getParentForm( this );
					handleBuyingAsBusinessChange( $form );
				}
			);

			$( 'input[name="wpfs-tax-id"]' ).change( function ( e ) {
				const $form = getParentForm( this );

				if ( isDynamicTaxesForm( $form ) || isStripeTaxForm( $form ) ) {
					refreshPricingFromServer( $form );
				}
			} );
		};

		function handleBuyingAsBusinessChange( $form ) {
			if (
				$( 'input[name="wpfs-buying-as-business"]', $form ).prop(
					'checked'
				)
			) {
				$( '#wpfs-business-name-row', $form ).show();
				$( '#wpfs-tax-id-row', $form ).show();
			} else {
				$( '#wpfs-business-name-row', $form ).hide();
				$( '#wpfs-tax-id-row', $form ).hide();
				$( 'input[name="wpfs-tax-id"]', $form ).val( '' );

				if ( isDynamicTaxesForm( $form ) || isStripeTaxForm( $form ) ) {
					refreshPricingFromServer( $form );
				}
			}
		}

		function setTaxIdTypeByCountry( $form ) {
			const countryCode = getTaxCountryCode( $form );
			let taxIdType = null;

			if ( wpfsTaxIdData[ countryCode ] ) {
				const countryItem = wpfsTaxIdData[ countryCode ];
				taxIdType = Object.values( countryItem )[ 0 ].id;
			}

			$( 'select[name="wpfs-tax-id-type"]', $form ).val( taxIdType );
			$( 'select[name="wpfs-tax-id-type"]', $form ).wpfsSelectmenu(
				'refresh'
			);
		}

		function handleTaxCountryChange( $form ) {
			if ( isDynamicTaxesForm( $form ) ) {
				handleTaxStateVisibility( $form );
			} else if ( isStripeTaxForm( $form ) ) {
				refreshTaxPostalCodeVisibility( $form );
			}

			if ( isDynamicTaxesForm( $form ) || isStripeTaxForm( $form ) ) {
				refreshPricingFromServer( $form );
			}

			setTaxIdTypeByCountry( $form );
			if ( isTaxIdCollectible( $form ) ) {
				refreshTaxIdPlaceholder( $form );
			}
		}

		function handleTaxStateChange( $form ) {
			if ( isDynamicTaxesForm( $form ) ) {
				refreshPricingFromServer( $form );
			}
		}

		function getTaxCountryCode( $form ) {
			let result;

			if (
				$( 'select[name="wpfs-tax-country"] option:selected', $form ) &&
				$( 'select[name="wpfs-tax-country"] option:selected', $form )
					.length > 0
			) {
				result = $(
					'select[name="wpfs-tax-country"] option:selected',
					$form
				).val();
			} else if (
				$(
					'select[name="wpfs-billing-address-country"] option:selected',
					$form
				) &&
				$(
					'select[name="wpfs-billing-address-country"] option:selected',
					$form
				).length > 0
			) {
				result = $(
					'select[name="wpfs-billing-address-country"] option:selected',
					$form
				).val();
			} else {
				result = null;
			}

			return result;
		}

		function getPlaceholderForTaxIdTypeAndCountry(
			taxIdType,
			countryCode
		) {
			let result = '';

			if ( wpfsTaxIdData[ countryCode ] ) {
				const countryItem = wpfsTaxIdData[ countryCode ];

				if ( countryItem[ taxIdType ] ) {
					result = countryItem[ taxIdType ].example;
				}
			}

			return result;
		}

		function refreshTaxIdPlaceholder( $form ) {
			const countryCode = getTaxCountryCode( $form );
			const taxIdType = getTaxIdType( $form );

			$( 'input[name="wpfs-tax-id"]', $form ).attr(
				'placeholder',
				getPlaceholderForTaxIdTypeAndCountry( taxIdType, countryCode )
			);
		}

		function getCountryCodesRequiringPostalCodeForTax() {
			return Object.getOwnPropertyNames( POSTAL_CODE_PLACEHOLDERS );
		}

		function handleTaxStateVisibility( $form ) {
			const selectedCountry = $(
				'select[name="wpfs-tax-country"] option:selected',
				$form
			).val();

			if ( selectedCountry === COUNTRY_CODE_UNITED_STATES ) {
				$( '#wpfs-tax-state-row', $form ).show();
			} else {
				$( '#wpfs-tax-state-row', $form ).hide();
			}
		}

		function getPostalCodePlaceholderForCountry( countryCode ) {
			return POSTAL_CODE_PLACEHOLDERS[ countryCode ]
				? POSTAL_CODE_PLACEHOLDERS[ countryCode ]
				: '';
		}

		function refreshTaxPostalCodeVisibility( $form ) {
			const countryCode = getTaxCountryCode( $form );

			if (
				getCountryCodesRequiringPostalCodeForTax().includes(
					countryCode
				)
			) {
				$( '#wpfs-tax-postal-code-row', $form ).show();
				$( 'input[name="wpfs-tax-zip"]', $form ).attr(
					'placeholder',
					getPostalCodePlaceholderForCountry( countryCode )
				);
			} else {
				$( '#wpfs-tax-postal-code-row', $form ).hide();
				$( 'input[name="wpfs-tax-zip"]', $form ).val( '' );
			}
		}

		function handleTaxIdTypeChange( $form ) {
			refreshTaxIdPlaceholder( $form );
		}

		function handleQuantityChange( $form ) {
			refreshPricingFromServer( $form );
		}

		function extractFormNameFromNode( $form ) {
			return $form.data( 'wpfs-form-id' );
		}

		function extractAmountSelectorStyleFromNode( $form ) {
			return $form.data( 'wpfs-selector-style' );
		}

		function extractTaxRateTypeFromNode( $form ) {
			return $form.data( 'wpfs-tax-rate-type' );
		}

		function setPaymentDetailsData( $form, data ) {
			const formName = extractFormNameFromNode( $form );

			WPFS.setPaymentDetails( formName, data );
		}

		class WPFP_ProductPriceSetter {
			constructor( $form ) {
				this.$form = $form;
				this.formName = extractFormNameFromNode( $form );
			}

			calculateGrossPricing( priceId ) {
				return WPFS.getPaymentDetails( this.formName )
					[ priceId ].filter(
						( lineItem ) =>
							! (
								lineItem.subType ===
									PRICE_LINE_ITEM_SUBTYPE_TAX &&
								lineItem.inclusive
							)
					)
					.reduce( ( sum, lineItem ) => {
						return sum + lineItem.amount;
					}, 0 );
			}
			getProductId( $product ) {
				return $product.attr( 'id' );
			}

			getProductZeroDecimalSupport( $product ) {
				return $product.data( 'wpfs-zero-decimal-support' );
			}

			getProductCurrencyCode( $product ) {
				return $product.data( 'wpfs-currency' );
			}

			getProductCurrencySymbol( $product ) {
				return $product.data( 'wpfs-currency-symbol' );
			}

			formatPriceForInputValue( $product, priceInCents ) {
				const zeroDecimalSupport =
					this.getProductZeroDecimalSupport( $product );

				let amount = priceInCents;
				if ( ! zeroDecimalSupport ) {
					amount /= 100;
				}

				return number_format(
					amount,
					zeroDecimalSupport ? 0 : 2,
					'.',
					''
				);
			}

			setProductLabel( formatter, $product, priceInCents ) {
				const zeroDecimalSupport =
					this.getProductZeroDecimalSupport( $product );

				let amount = priceInCents;
				if ( ! zeroDecimalSupport ) {
					amount /= 100;
				}

				const formattedAmount = formatter.format(
					amount,
					this.getProductCurrencyCode( $product ),
					this.getProductCurrencySymbol( $product ),
					zeroDecimalSupport
				);

				this.formatProductLabel( $product, formattedAmount );
			}

			findProductInput( priceId ) {
				// Dummy implementation
				throw new Error(
					'WPFP_ProductPriceSetter.findProductInput() - Dont call directly, implement a subclass'
				);
			}

			getProductName( $product ) {
				return $product.data( 'wpfs-product-name' );
			}

			formatProductLabel( $product, formattedAmount ) {
				// Dummy implementation
				throw new Error(
					'WPFP_ProductPriceSetter.formatProductLabel() - Dont call directly, implement a subclass'
				);
			}

			refresh() {}
		}

		class WPFP_OneTimePriceSetter extends WPFP_ProductPriceSetter {
			constructor( $form ) {
				super( $form );
			}

			setProductPrice( $product, priceInCents ) {
				$product.data(
					'wpfs-amount-in-smallest-common-currency',
					priceInCents
				);
				$product.val(
					this.formatPriceForInputValue( $product, priceInCents )
				);
			}

			setPricesAndLabels( priceId ) {
				const $product = this.findProductInput( priceId );
				const grossPriceInCents = this.calculateGrossPricing( priceId );

				this.setProductPrice( $product, grossPriceInCents );
				this.setProductLabel(
					createCurrencyFormatter( this.$form ),
					$product,
					grossPriceInCents
				);
			}
		}

		class WPFP_PriceSetter_PaymentRadioList extends WPFP_OneTimePriceSetter {
			constructor( $form ) {
				super( $form );
			}

			findProductInput( priceId ) {
				return $(
					'input[data-wpfs-amount-price-id="' + priceId + '"]',
					this.$form
				);
			}

			formatProductLabel( $product, formattedAmount ) {
				const formattedLabel =
					this.getProductName( $product ) + ' -  ' + formattedAmount;
				$product.data( 'wpfs-amount-description', formattedLabel );
				$(
					'label[for="' + this.getProductId( $product ) + '"]',
					this.$form
				).text( formattedLabel );
			}
		}

		class WPFP_PriceSetter_PaymentDropdown extends WPFP_OneTimePriceSetter {
			constructor( $form ) {
				super( $form );
			}

			findProductInput( priceId ) {
				return $(
					'option[data-wpfs-amount-price-id="' + priceId + '"]',
					this.$form
				);
			}

			formatProductLabel( $product, formattedAmount ) {
				const formattedLabel =
					this.getProductName( $product ) + ' -  ' + formattedAmount;
				$product.data( 'wpfs-amount-description', formattedLabel );
				$product.text( formattedLabel );
			}

			refresh() {
				this.$form
					.find( '[data-toggle="selectmenu"]' )
					.wpfsSelectmenu( 'refresh' );
			}
		}

		class WPFP_PriceSetter_PaymentButtonGroup extends WPFP_OneTimePriceSetter {
			constructor( $form ) {
				super( $form );
			}

			findProductInput( priceId ) {
				return $(
					'input[data-wpfs-amount-price-id="' + priceId + '"]',
					this.$form
				);
			}

			formatProductLabel( $product, formattedAmount ) {
				const formattedLabel = formattedAmount;
				$product.data( 'wpfs-amount-description', formattedLabel );
				$(
					'label[for="' + this.getProductId( $product ) + '"]',
					this.$form
				).text( formattedLabel );
			}
		}

		class WPFP_PriceSetter_PaymentHidden extends WPFP_OneTimePriceSetter {
			constructor( $form ) {
				super( $form );
			}

			findProductInput( priceId ) {
				return $(
					'input[data-wpfs-amount-price-id="' + priceId + '"]',
					this.$form
				);
			}

			formatProductLabel( $product, formattedAmount ) {
				$product.data( 'wpfs-amount-description', formattedAmount );
			}
		}

		class WPFP_SubscriptionPriceSetter extends WPFP_ProductPriceSetter {
			constructor( $form ) {
				super( $form );
			}

			calculateGrossComponentPricing( priceId, component ) {
				return WPFS.getPaymentDetails( this.formName )
					[ priceId ].filter(
						( lineItem ) => lineItem.type === component
					)
					.filter(
						( lineItem ) =>
							! (
								lineItem.subType ===
									PRICE_LINE_ITEM_SUBTYPE_TAX &&
								lineItem.inclusive
							)
					)
					.reduce( ( sum, lineItem ) => {
						return sum + lineItem.amount;
					}, 0 );
			}

			setProductPrice(
				$product,
				setupFeePriceInCents,
				productPriceInCents
			) {
				$product.data(
					'wpfs-plan-setup-fee-in-smallest-common-currency',
					setupFeePriceInCents
				);
				$product.data(
					'wpfs-plan-amount-in-smallest-common-currency',
					productPriceInCents
				);

				$product.data(
					'wpfs-plan-amount-in-smallest-common-currency',
					this.formatPriceForInputValue(
						$product,
						setupFeePriceInCents
					)
				);
				$product.data(
					'wpfs-plan-amount-in-smallest-common-currency',
					this.formatPriceForInputValue(
						$product,
						productPriceInCents
					)
				);
			}

			getPriceAndIntervalLabel(
				interval,
				intervalCount,
				formattedAmount
			) {
				let formatString, priceLabel;

				switch ( interval ) {
					case INTERVAL_DAY:
						formatString =
							intervalCount === 1
								? wpfsFormSettings.l10n
										.subscription_pricing_templates.daily
								: wpfsFormSettings.l10n
										.subscription_pricing_templates.x_days;
						break;

					case INTERVAL_WEEK:
						formatString =
							intervalCount === 1
								? wpfsFormSettings.l10n
										.subscription_pricing_templates.weekly
								: wpfsFormSettings.l10n
										.subscription_pricing_templates.x_weeks;
						break;

					case INTERVAL_MONTH:
						formatString =
							intervalCount === 1
								? wpfsFormSettings.l10n
										.subscription_pricing_templates.monthly
								: wpfsFormSettings.l10n
										.subscription_pricing_templates
										.x_months;
						break;

					case INTERVAL_YEAR:
						formatString =
							intervalCount === 1
								? wpfsFormSettings.l10n
										.subscription_pricing_templates.yearly
								: wpfsFormSettings.l10n
										.subscription_pricing_templates.x_years;
						break;
				}

				if ( intervalCount === 1 ) {
					priceLabel = sprintf( formatString, formattedAmount );
				} else {
					priceLabel = sprintf(
						formatString,
						formattedAmount,
						intervalCount
					);
				}

				return priceLabel;
			}

			setProductLabel( formatter, $product, priceInCents ) {
				const zeroDecimalSupport =
					this.getProductZeroDecimalSupport( $product );

				let amount = priceInCents;
				if ( ! zeroDecimalSupport ) {
					amount /= 100;
				}

				const formattedAmount = formatter.format(
					amount,
					this.getProductCurrencyCode( $product ),
					this.getProductCurrencySymbol( $product ),
					zeroDecimalSupport
				);
				const interval = $product.data( 'wpfs-interval' );
				const intervalCount = $product.data( 'wpfs-interval-count' );

				this.formatProductLabel(
					$product,
					this.getPriceAndIntervalLabel(
						interval,
						intervalCount,
						formattedAmount
					)
				);
			}

			setPricesAndLabels( priceId ) {
				const $product = this.findProductInput( priceId );

				const grossSetupFeeInCents =
					this.calculateGrossComponentPricing(
						priceId,
						PRICE_LINE_ITEM_TYPE_SETUP_FEE
					);
				const grossProductPriceInCents =
					this.calculateGrossComponentPricing(
						priceId,
						PRICE_LINE_ITEM_TYPE_PRODUCT
					);

				this.setProductPrice(
					$product,
					grossSetupFeeInCents,
					grossProductPriceInCents
				);
				this.setProductLabel(
					createCurrencyFormatter( this.$form ),
					$product,
					grossProductPriceInCents
				);
			}
		}

		class WPFP_PriceSetter_SubscriptionRadioList extends WPFP_SubscriptionPriceSetter {
			constructor( $form ) {
				super( $form );
			}

			findProductInput( priceId ) {
				return $(
					'input[data-wpfs-value="' + priceId + '"]',
					this.$form
				);
			}

			formatProductLabel( $product, formattedAmount ) {
				const formattedLabel =
					this.getProductName( $product ) + ' -  ' + formattedAmount;
				$product.data( 'wpfs-amount-description', formattedLabel );
				$(
					'label[for="' + this.getProductId( $product ) + '"]',
					this.$form
				).text( formattedLabel );
			}
		}

		class WPFP_PriceSetter_SubscriptionDropdown extends WPFP_SubscriptionPriceSetter {
			constructor( $form ) {
				super( $form );
			}

			findProductInput( priceId ) {
				return $(
					'option[data-wpfs-value="' + priceId + '"]',
					this.$form
				);
			}

			formatProductLabel( $product, formattedAmount ) {
				const formattedLabel =
					this.getProductName( $product ) + ' -  ' + formattedAmount;
				$product.data( 'wpfs-amount-description', formattedLabel );
				$product.text( formattedLabel );
			}

			refresh() {
				this.$form
					.find( '[data-toggle="selectmenu"]' )
					.wpfsSelectmenu( 'refresh' );
			}
		}

		function createProductPriceSetter( $form, priceId, grossPriceInCents ) {
			const formType = $form.data( FROM_TYPE_DOM );

			if (
				formType === FORM_TYPE_INLINE_PAYMENT ||
				formType === FORM_TYPE_CHECKOUT_PAYMENT
			) {
				switch ( extractAmountSelectorStyleFromNode( $form ) ) {
					case SELECTOR_STYLE_RADIO_BUTTONS:
						return new WPFP_PriceSetter_PaymentRadioList( $form );
					case SELECTOR_STYLE_DROPDOWN:
						return new WPFP_PriceSetter_PaymentDropdown( $form );
					case SELECTOR_STYLE_BUTTON_GROUP:
						return new WPFP_PriceSetter_PaymentButtonGroup( $form );
					case undefined:
					default:
						return new WPFP_PriceSetter_PaymentHidden( $form );
				}
			} else if (
				formType === FORM_TYPE_INLINE_SUBSCRIPTION ||
				formType === FORM_TYPE_CHECKOUT_SUBSCRIPTION
			) {
				switch ( extractAmountSelectorStyleFromNode( $form ) ) {
					case SELECTOR_STYLE_RADIO_BUTTONS:
						return new WPFP_PriceSetter_SubscriptionRadioList(
							$form
						);
					case SELECTOR_STYLE_DROPDOWN:
						return new WPFP_PriceSetter_SubscriptionDropdown(
							$form
						);
				}
			}
		}

		function refreshProductPricing( $form ) {
			const pricingData = WPFS.getPaymentDetails(
				extractFormNameFromNode( $form )
			);
			const productPriceSetter = createProductPriceSetter( $form );

			for ( const priceId in pricingData ) {
				if ( priceId === PRICE_ID_CUSTOM_AMOUNT ) {
					continue;
				}

				productPriceSetter.setPricesAndLabels( priceId );
			}

			productPriceSetter.refresh();
		}

		function refreshPaymentDetails( $form ) {
			const formType = $form.data( FROM_TYPE_DOM );

			if (
				formType === FORM_TYPE_INLINE_PAYMENT ||
				formType === FORM_TYPE_CHECKOUT_PAYMENT ||
				formType === FORM_TYPE_INLINE_DONATION ||
				formType === FORM_TYPE_CHECKOUT_DONATION
			) {
				handleAmountChange( $form );
			} else if (
				formType === FORM_TYPE_INLINE_SUBSCRIPTION ||
				formType === FORM_TYPE_CHECKOUT_SUBSCRIPTION
			) {
				handlePlanChange( $form );
			}
		}

		function createCustomAmountPricingData( $form, customAmount ) {
			return [
				{
					type: 'product',
					subType: 'product',
					id: PRICE_ID_CUSTOM_AMOUNT,
					displayName:
						wpfsFormSettings.l10n.products.other_amount_label,
					currency: $( '.wpfs-custom-amount--unique', $form ).data(
						'wpfs-currency'
					),
					amount: customAmount,
				},
			];
		}

		function refreshOtherPricingLocally( $form ) {
			const customAmount = parseCustomAmountUnique( $form );

			if ( ! isNaN( customAmount ) ) {
				WPFS.setPaymentDetailsForPrice(
					extractFormNameFromNode( $form ),
					PRICE_ID_CUSTOM_AMOUNT,
					createCustomAmountPricingData( $form, customAmount )
				);

				refreshPaymentDetails( $form );
			}
		}

		function refreshPricingFromServer( $form ) {
			const data = gatherFormDataForTaxCalculation( $form );
			data.action = 'wpfs-calculate-pricing';
			clearGlobalMessage( $form );
			clearFieldErrors( $form );
			showLoadingAnimation( $form );
			disableFormInputsSelectsButtons( $form );

			$.ajax( {
				type: 'POST',
				url: wpfsFormSettings.ajaxUrl,
				data,
				cache: false,
				dataType: 'json',
				success( data ) {
					if ( data.success ) {
						setPaymentDetailsData( $form, data.productPricing );
						refreshProductPricing( $form );
						refreshPaymentDetails( $form );
					} else if ( data.bindingResult ) {
						processValidationErrors( $form, data );
					} else {
						showErrorGlobalMessage(
							$form,
							wpfsFormSettings.l10n.validation_errors
								.internal_error_title,
							data.msg
						);
					}
				},
				error( jqXHR, textStatus, errorThrown ) {
					logError(
						'refreshPricingFromServer',
						jqXHR,
						textStatus,
						errorThrown
					);
				},
				complete() {
					hideLoadingAnimation( $form );
					enableFormInputsSelectsButtons( $form );
				},
			} );
		}

		function readCouponData() {
			if ( typeof wpfsCouponData !== 'undefined' ) {
				for ( const key in wpfsCouponData ) {
					if (
						wpfsCouponData.hasOwnProperty( key ) &&
						wpfsCouponData[ key ]
					) {
						WPFS.setCoupon( key, wpfsCouponData[ key ] );

						$form = getParentForm(
							$(
								"input[name='wpfs-form-name'][value='" +
									key +
									"']"
							)
						);
						$( "input[name='wpfs-coupon']", $form ).val(
							wpfsCouponData[ key ].name
						);
					}
				}
			}
		}

		function readProductPricing() {
			if ( typeof wpfsProductPricing !== 'undefined' ) {
				for ( const key in wpfsProductPricing ) {
					if ( wpfsProductPricing.hasOwnProperty( key ) ) {
						WPFS.setPaymentDetails(
							key,
							wpfsProductPricing[ key ]
						);
					}
				}
			}
		}

		WPFS.ready = function () {
			if ( debugLog ) {
				logInfo( 'ready', 'CALLED' );
			}

			readProductPricing();
			readCouponData();

			// trigger plan change to initialize payment summary
			const $planSelectElements = $(
				'select.wpfs-subscription-plan-select'
			);
			$planSelectElements.each( function () {
				const $form = getParentForm( this );
				if ( $( 'option:selected', $( this ) ).length ) {
					$( this ).val( $( 'option:selected', $( this ) ).val() );
				} else {
					$( this ).val( $( 'option:first', $( this ) ).val() );
				}
				handlePlanChange( $form );
			} );
			const $planRadioElements = $(
				'input.wpfs-subscription-plan-radio'
			);
			$planRadioElements.each( function () {
				const $form = getParentForm( this );
				if (
					$(
						'input:radio[name="wpfs-plan"]:not(:disabled):checked',
						$form
					).length === 0
				) {
					$(
						'input:radio[name="wpfs-plan"]:not(:disabled):first',
						$form
					).attr( 'checked', true );
				}
				handlePlanChange( $form );
			} );
			const $planHiddenElements = $(
				'input.wpfs-subscription-plan-hidden'
			);
			$planHiddenElements.each( function () {
				const $form = getParentForm( this );
				handlePlanChange( $form );
			} );

			const $customSelectElements = $(
				'select.wpfs-custom-amount-select'
			);
			$customSelectElements.each( function () {
				const $form = getParentForm( this );
				if ( $( 'option:selected', $( this ) ).length ) {
					$( this ).val( $( 'option:selected', $( this ) ).val() );
				} else {
					$( this ).val( $( 'option:first', $( this ) ).val() );
				}
				handleAmountChange( $form );
			} );
			const $customRadioElements = $( 'input.wpfs-custom-amount-radio' );
			$customRadioElements.each( function () {
				const $form = getParentForm( this );
				if (
					$(
						'input:radio[name="wpfs-custom-amount"]:not(:disabled):checked',
						$form
					).length === 0
				) {
					$(
						'input:radio[name="wpfs-custom-amount"]:not(:disabled):first',
						$form
					).attr( 'checked', true );
				}
				handleAmountChange( $form );
			} );
			const $customHiddenElements = $(
				'input.wpfs-custom-amount-hidden'
			);
			$customHiddenElements.each( function () {
				const $form = getParentForm( this );
				handleAmountChange( $form );
			} );
			const $paymentFormsWithCustomAmount = $(
				'form[data-wpfs-amount-type="custom_amount"]'
			);
			$paymentFormsWithCustomAmount.each( function () {
				const $form = $( this );
				handleAmountChange( $form );
			} );

			// $('input.wpfs-custom-amount').each(function (index) {
			//     var $form = getParentForm(this);
			//     $form.find('input.wpfs-custom-amount').first().click();
			// });
			//
			// // tnagy trigger payment amount change to initialize payment summary
			// var $paymentsFormWithListOfAmounts = $('form[data-wpfs-amount-type="list_of_amounts"]');
			// $paymentsFormWithListOfAmounts.each(function () {
			//     var $form = $(this);
			//     handleAmountChange($form);
			// });
			// var $paymentFormsWithSpecifiedAmount = $('form[data-wpfs-amount-type="specified_amount"]');
			// $paymentFormsWithSpecifiedAmount.each(function () {
			//     var $form = $(this);
			//     handleAmountChange($form);
			// });
			// var $paymentFormsWithCustomAmount = $('form[data-wpfs-amount-type="custom_amount"]');
			// $paymentFormsWithCustomAmount.each(function () {
			//     var $form = $(this);
			//     handleAmountChange($form);
			// });

			const $spinnerElements = $( 'input[name="wpfs-plan-quantity"]' );
			$spinnerElements.each( function () {
				const $form = getParentForm( this );
				$( 'input[name="wpfs-plan-quantity"]', $form ).on(
					'spinchange',
					function ( e, ui ) {
						handleQuantityChange( $form );
					}
				);
			} );
		};

		// tnagy initialize components
		WPFS.initInputGroup();
		WPFS.initSelectmenu();
		WPFS.initAddressSwitcher();
		WPFS.initTooltip();
		WPFS.initStepper();
		WPFS.initDatepicker();
		WPFS.initCombobox();
		WPFS.initCustomAmount();
		WPFS.initStripeJSCard();
		WPFS.initCoupon();
		WPFS.initCheckoutForms();
		WPFS.initInlineForms();
		WPFS.initReCaptcha();
		WPFS.initPlanRadioButton();
		WPFS.initBuyingAsBusiness();
		WPFS.initTaxFieldChanges();

		WPFS.ready();

		// to catch redirect from stripe
		// url has payment_intent and redirect_status parameters
		const urlParams = new URLSearchParams( window.location.search );
		const paymentIntent = urlParams.get( 'payment_intent' );
		const redirectStatus = urlParams.get( 'redirect_status' );
		const paymentIntentClientSecret = urlParams.get(
			'payment_intent_client_secret'
		);
		const formId = urlParams.get( 'wpfs-form-id' );

		$form = $( `form[data-wpfs-form-id="${ formId }"]` );

		// handle payment intent redirects
		if (
			paymentIntent &&
			redirectStatus &&
			redirectStatus === 'succeeded'
		) {
			// lock the screen to prevent user interaction
			disableFormButtons( $form );
			showLoadingAnimation( $form );
			clearFieldErrors( $form );
			clearGlobalMessage( $form );

			let data = createFormSerializer( $form ).serialize();
			data = JSON.parse(
				'{"' +
					data.replace( /&/g, '","' ).replace( /=/g, '":"' ) +
					'"}',
				function ( key, value ) {
					return key === '' ? value : decodeURIComponent( value );
				}
			);
			data.action = 'wp_full_stripe_confirm_redirect';
			data[ 'wpfs-stripe-payment-intent-id' ] = paymentIntent;
			data.redirect_status = redirectStatus;
			data.payment_intent_client_secret = paymentIntentClientSecret;

			$.ajax( {
				type: 'POST',
				url: wpfsFormSettings.ajaxUrl,
				data,
				cache: false,
				dataType: 'json',
				success( data ) {
					if ( debugLog ) {
						logInfo(
							'stripeConfirmRedirect',
							'SUCCESS response=' + JSON.stringify( data )
						);
					}
					if ( data.success ) {
						if ( data.redirectURL ) {
							location.href = data.redirectURL;
						} else {
							// reset form fields
							resetFormFields( $form, null );

							// show success message
							showSuccessGlobalMessage(
								$form,
								data.messageTitle,
								data.message
							);

							// enable submit button
							enableFormButtons( $form );

							// stop the animations
							hideLoadingAnimation( $form );

							// clear the parameters
							window.history.replaceState(
								{},
								document.title,
								window.location.pathname
							);
						}
					} else {
						if ( data && ( data.messageTitle || data.message ) ) {
							showErrorGlobalMessage(
								$form,
								data.messageTitle,
								data.message
							);
						}
						processValidationErrors( $form, data );
					}
				},
				error( jqXHR, textStatus, errorThrown ) {
					logError(
						'stripeConfirmRedirect',
						jqXHR,
						textStatus,
						errorThrown
					);
					showErrorGlobalMessage(
						$form,
						wpfsFormSettings.l10n.validation_errors
							.internal_error_title,
						wpfsFormSettings.l10n.validation_errors.internal_error
					);
				},
				complete() {},
			} );
		} else if (
			paymentIntent &&
			redirectStatus &&
			redirectStatus === 'failed'
		) {
			// show error message
			showErrorGlobalMessage(
				$form,
				'Payment failed',
				'Payment failed, please try again.'
			);

			// clear the parameters
			window.history.replaceState(
				{},
				document.title,
				window.location.pathname
			);
		} else if ( paymentIntent ) {
			// customer was redirected back to us, but without a status indicator
			// we'll need to get the payment intent to see the result of the transaction
		}
	} );
} )( jQuery );
