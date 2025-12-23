<?php

/**
 * Class MM_WPFS_Stripe
 *
 * deals with calls to Stripe API
 *
 */
class MM_WPFS_Stripe {
	use MM_WPFS_Logger_AddOn;
	use MM_WPFS_StaticContext_AddOn;

	const DESIRED_STRIPE_API_VERSION = '2025-06-30.basil';

	/**
	 * @var string
	 */
	const INVALID_NUMBER_ERROR = 'invalid_number';
	/**
	 * @var string
	 */
	const INVALID_NUMBER_ERROR_EXP_MONTH = 'invalid_number_exp_month';
	/**
	 * @var string
	 */
	const INVALID_NUMBER_ERROR_EXP_YEAR = 'invalid_number_exp_year';
	/**
	 * @var string
	 */
	const INVALID_EXPIRY_MONTH_ERROR = 'invalid_expiry_month';
	/**
	 * @var string
	 */
	const INVALID_EXPIRY_YEAR_ERROR = 'invalid_expiry_year';
	/**
	 * @var string
	 */
	const INVALID_CVC_ERROR = 'invalid_cvc';
	/**
	 * @var string
	 */
	const INCORRECT_NUMBER_ERROR = 'incorrect_number';
	/**
	 * @var string
	 */
	const EXPIRED_CARD_ERROR = 'expired_card';
	/**
	 * @var string
	 */
	const INCORRECT_CVC_ERROR = 'incorrect_cvc';
	/**
	 * @var string
	 */
	const INCORRECT_ZIP_ERROR = 'incorrect_zip';
	/**
	 * @var string
	 */
	const CARD_DECLINED_ERROR = 'card_declined';
	/**
	 * @var string
	 */
	const MISSING_ERROR = 'missing';
	/**
	 * @var string
	 */
	const PROCESSING_ERROR = 'processing_error';
	/**
	 * @var string
	 */
	const MISSING_PAYMENT_INFORMATION = 'missing_payment_information';
	/**
	 * @var string
	 */
	const COULD_NOT_FIND_PAYMENT_INFORMATION = 'Could not find payment information';

	/* @var $stripe \StripeWPFS\Stripe\StripeClient */
	public $stripe;

	/* @var MM_WPFS_Options */
	private $options;

	/* @var string */
	private $apiMode;

	/* @var bool */
	private $usingWpTestPlatform;

	/* @var bool */
	private $usingWpLivePlatform;

	/* @var string */
	private $liveStripeAcountId;

	/* @var string */
	private $testStripeAcountId;

	/* @var bool */
	private $validLicense;

	/* @var string */
	private $connectMode;

	/* @var string */
	private $connectUrl;

	/* @var  string */
	private $userVersion;

	/**
	 * MM_WPFS_Stripe constructor.
	 *
	 * @param $token
	 *
	 * @throws Exception
	 */
	public function __construct( $token, $loggerService ) {
		$this->initLogger( $loggerService, MM_WPFS_LoggerService::MODULE_STRIPE );
		$this->options = new MM_WPFS_Options();

		$this->initStaticContext();
		$this->apiMode = $this->options->get( MM_WPFS_Options::OPTION_API_MODE );
		$this->usingWpTestPlatform = $this->options->get( MM_WPFS_Options::OPTION_USE_WP_TEST_PLATFORM );
		$this->usingWpLivePlatform = $this->options->get( MM_WPFS_Options::OPTION_USE_WP_LIVE_PLATFORM );
		$this->liveStripeAcountId = $this->options->get( MM_WPFS_Options::OPTION_LIVE_ACCOUNT_ID );
		$this->testStripeAcountId = $this->options->get( MM_WPFS_Options::OPTION_TEST_ACCOUNT_ID );
		$this->connectUrl = $this->options->getFunctionsUrl();
		$this->userVersion = MM_WPFS::get_user_version();

		if ( ! is_null( $token ) && ! empty( $token ) ) {
			try {
				$this->stripe = self::createStripeClient( $token );
			} catch (Exception $ex) {
				$this->logger->error( __FUNCTION__, 'Error while initializing the Stripe client', $ex );
			}
		}
		$this->validLicense = WPFS_License::is_active();
	}

	/**
	 * @param $token string
	 * @return \StripeWPFS\Stripe\StripeClient
	 */
	public static function createStripeClient( $token ) {
		return new \StripeWPFS\Stripe\StripeClient( [ 
			"api_key" => $token,
			"stripe_version" => self::DESIRED_STRIPE_API_VERSION
		] );
	}

	/**
	 * @param $context MM_WPFS_StaticContext
	 * @param $liveMode
	 * @return mixed
	 */
	public static function getStripeAuthenticationTokenByMode( $context, $liveMode ) {
		$optionKey = $liveMode ? MM_WPFS_Options::OPTION_API_LIVE_SECRET_KEY : MM_WPFS_Options::OPTION_API_TEST_SECRET_KEY;

		return $context->getOptions()->get( $optionKey );
	}

	/**
	 * @param $context MM_WPFS_StaticContext
	 * @return string
	 */
	public static function getStripeAuthenticationToken( $context ) {
		return MM_WPFS_Stripe::getStripeAuthenticationTokenByMode( $context, self::isStripeApiInLiveMode( $context ) );
	}

	/**
	 * @param $context MM_WPFS_StaticContext
	 * @return bool
	 */
	public static function isStripeApiInLiveMode( $context ) {
		return $context->getOptions()->get( MM_WPFS_Options::OPTION_API_MODE ) === MM_WPFS::STRIPE_API_MODE_LIVE;
	}

	/**
	 * @return mixed
	 */
	public static function getStripeTestAuthenticationToken( $context ) {
		return $context->getOptions()->get( MM_WPFS_Options::OPTION_API_TEST_SECRET_KEY );
	}

	/**
	 * @return mixed
	 */
	public static function getStripeLiveAuthenticationToken( $context ) {
		return $context->getOptions()->get( MM_WPFS_Options::OPTION_API_LIVE_SECRET_KEY );
	}

	function getErrorCodes() {
		return [
			self::INVALID_NUMBER_ERROR,
			self::INVALID_NUMBER_ERROR_EXP_MONTH,
			self::INVALID_NUMBER_ERROR_EXP_YEAR,
			self::INVALID_EXPIRY_MONTH_ERROR,
			self::INVALID_EXPIRY_YEAR_ERROR,
			self::INVALID_CVC_ERROR,
			self::INCORRECT_NUMBER_ERROR,
			self::EXPIRED_CARD_ERROR,
			self::INCORRECT_CVC_ERROR,
			self::INCORRECT_ZIP_ERROR,
			self::CARD_DECLINED_ERROR,
			self::MISSING_ERROR,
			self::PROCESSING_ERROR,
			self::MISSING_PAYMENT_INFORMATION
		];
	}

	/**
	 * @throws WPFS_UserFriendlyException
	 */
	function remoteRequest( $method, $url, $body = null ) {
		$response = null;
		if ( $method === 'get' ) {
			$response = wp_remote_get( $this->connectUrl . $url,
				[
					'timeout' => 10
				] );
		} else if ( $method === 'post' ) {
			$payload = null;
			// if there is a valid license, we need to send the license id and user id to the cloud function
			if ( is_array( $body ) && isset( $body["validLicense"] ) && $body["validLicense"] === true ) {
				// add the license id and user id to the body before sending it
				if ( WPFS_License::get_user_id() ) {
					$license_user_id = WPFS_License::get_user_id();
					$body["licenseUserId"] = $license_user_id;
				}
				if ( WPFS_License::get_key() ) {
					$license_id = WPFS_License::get_key();
					$body["licenseId"] = $license_id;
				}
			}
			if ( isset( $body ) && ! empty( $body ) ) {
				if ( isset( $body['amount'] ) ) {
					// make the amount an integer to avoid issues when encoding the payload
					$body['amount'] = intval( $body['amount'] );
				}
				$payload = json_encode( $body );
				if ( $payload == false ) {
					$this->logger->error(
						__FUNCTION__,
						'Error while encoding the payload: ' . json_last_error_msg()
					);
					throw new WPFS_UserFriendlyException( 'Error while encoding the payload: ' . json_last_error_msg() );
				}
			}
			$response = wp_remote_post(
				$this->connectUrl . $url,
				[
					'headers' => [ 'Content-Type' => 'application/json; charset=utf-8' ],
					'body' => $payload,
					'timeout' => 10
				]
			);
		} else if ( $method === 'delete' ) {
			$response = wp_remote_request( $this->connectUrl . $url, [ 'method' => 'DELETE', 'timeout' => 10 ] );
		}

		if ( is_wp_error( $response ) ) {
			$error_message = $response->get_error_message();
			$this->logger->error( __FUNCTION__, 'Transport error: ' . $error_message );
			throw new WPFS_UserFriendlyException( 'API request failed: ' . $error_message );
		}

		$response_code = wp_remote_retrieve_response_code( $response );
		$response_body = wp_remote_retrieve_body( $response );
		$decoded_body  = json_decode( $response_body ); // decode as object

		if ( $response_code !== 200 || ! is_object( $decoded_body ) || empty( $decoded_body->success ) ) {
			$error_detail = isset( $decoded_body->error ) ? $decoded_body->error : 'Unknown error';
			$context      = isset( $decoded_body->context ) ? ' (' . $decoded_body->context . ')' : '';
			$this->logger->error(
				__FUNCTION__,
				"API error {$context}: " . $error_detail
			);

			throw new WPFS_UserFriendlyException( "API error {$context}: " . $error_detail );
		}

		return $decoded_body->data;
	}

	/**
	 * Builds a query string.
	 *
	 * @param array $params Query parameters.
	 * @return string Query string.
	 */
	public function build_query( array $params ): string {
		$parts = [];

		foreach ( $params as $key => $value ) {
			if ( is_array( $value ) ) {
				// Repeat key=value for each array item
				foreach ( $value as $item ) {
					$parts[] = urlencode( $key ) . '=' . urlencode( $item );
				}
			} else {
				// Handle scalar values normally
				$parts[] = urlencode( $key ) . '=' . urlencode( $value );
			}
		}

		return implode( '&', $parts );
	}

	/**
	 * @param string $stripeCustomerId
	 * @param string $stripePlanId
	 * @param float $amount
	 *
	 * @return \StripeWPFS\Stripe\Subscription
	 * @throws Exception
	 */
	public function subscribeCustomerToPlan( $stripeCustomerId, $stripePlanId, $amount ) {
		$subscriptionData = [
			'customer' => $stripeCustomerId,
			'items' => [
				[
					'price' => $stripePlanId,
					'quantity' => $amount
				]
			]
		];

		if ( $this->apiMode === 'test' && $this->usingWpTestPlatform ) {
			$subscriptionData = array_merge( $subscriptionData, [ 'validLicense' => $this->validLicense ] );
			$stripeSubscription = $this->remoteRequest(
				'post',
				'/subscriptions?mode=test&accountId=' . $this->testStripeAcountId . '&apiVersion=' . $this->userVersion,
				$subscriptionData
			);
		} elseif ( $this->apiMode === 'live' && $this->usingWpLivePlatform ) {
			$subscriptionData = array_merge( $subscriptionData, [ 'validLicense' => $this->validLicense ] );
			$stripeSubscription = $this->remoteRequest(
				'post',
				'/subscriptions?mode=live&accountId=' . $this->liveStripeAcountId . '&apiVersion=' . $this->userVersion,
				$subscriptionData
			);
		} else {
			$stripeSubscription = json_decode( $this->stripe->subscriptions->create( $subscriptionData )->toJSON() );
		}

		return $stripeSubscription;
	}

	/**
	 * @param $stripePaymentMethodId
	 *
	 * @return \StripeWPFS\Stripe\PaymentMethod
	 * @throws \StripeWPFS\Stripe\Exception\ApiErrorException
	 * @throws Exception
	 */
	public function validatePaymentMethodCVCCheck( $stripePaymentMethodId ) {
		/* @var $paymentMethod \StripeWPFS\Stripe\PaymentMethod */
		$paymentMethod = null;
		if ( $this->apiMode === 'test' && $this->usingWpTestPlatform ) {
			$paymentMethod = $this->remoteRequest(
				'get',
				'/payment_methods/' . $stripePaymentMethodId . '?mode=test&accountId=' . $this->testStripeAcountId
			);
		} elseif ( $this->apiMode === 'live' && $this->usingWpLivePlatform ) {
			$paymentMethod = $this->remoteRequest(
				'get',
				'/payment_methods/' . $stripePaymentMethodId . '?mode=live&accountId=' . $this->liveStripeAcountId
			);
		} else {
			$paymentMethod = json_decode( $this->stripe->paymentMethods->retrieve( $stripePaymentMethodId )->toJSON() );
		}

		if ( $paymentMethod->type === 'card' && is_null( $paymentMethod->card->checks->cvc_check ) && is_null( $paymentMethod->card->wallet ) ) {
			throw new Exception(
				/* translators: Validation error message for a card number without a CVC code */
				__( 'Please enter a CVC code', 'wp-full-stripe-free' )
			);
		}

		return $paymentMethod;
	}

	/**
	 * @param $ctx MM_WPFS_CreateSubscriptionContext
	 * @param $options MM_WPFS_CreateSubscriptionOptions
	 * @throws \StripeWPFS\Stripe\Exception\ApiErrorException
	 * @throws WPFS_UserFriendlyException
	 * @return \StripeWPFS\Stripe\Subscription
	 */
	public function createSubscriptionForCustomer( $ctx, $options ) {
		$stripeCustomer = $this->retrieveCustomer( $ctx->stripeCustomerId );

		$useTestFunctions = $this->apiMode === 'test' && $this->usingWpTestPlatform;
		$useLiveFunctions = $this->apiMode === 'live' && $this->usingWpLivePlatform;

		$recurringPrice = null;
		if ( $useTestFunctions ) {
			$recurringPrice = $this->remoteRequest(
				'get',
				'/prices/' . $ctx->stripePriceId . '?mode=test&accountId=' . $this->testStripeAcountId
			);
		} elseif ( $useLiveFunctions ) {
			$recurringPrice = $this->remoteRequest(
				'get',
				'/prices/' . $ctx->stripePriceId . '?mode=live&accountId=' . $this->liveStripeAcountId
			);
		} else {
			$recurringPrice = json_decode( $this->stripe->prices->retrieve( $ctx->stripePriceId )->toJSON() );
		}

		if ( ! isset( $recurringPrice ) ) {
			throw new Exception( "Recurring price with id '" . $ctx->stripePriceId . " doesn't exist." );
		}

		if ( ! is_null( $ctx->stripePaymentMethodId ) ) {
			$paymentMethod = $this->retrievePaymentMethod( $ctx->stripePaymentMethodId );
			$this->attachPaymentMethodToCustomer( $paymentMethod, $stripeCustomer->id );

			$params = [
				'invoice_settings' => [
					'default_payment_method' => $ctx->stripePaymentMethodId
				]
			];
			$this->updateCustomerDetails( $stripeCustomer, $params );
		}

		if ( $ctx->setupFee > 0 ) {
			$setupFeeParams = [
				'customer' => $stripeCustomer->id,
				'currency' => $recurringPrice->currency,
				'description' => sprintf(
					/* translators: It's a line item for the initial payment of a subscription */
					__( 'One-time setup fee (plan: %s)', 'wp-full-stripe-free' ),
					MM_WPFS_Localization::translateLabel( $ctx->productName )
				),
				'quantity' => $ctx->stripePlanQuantity,
				'unit_amount_decimal' => $ctx->setupFee,
				'metadata' => [ 
					'type' => 'setupFee',
					'webhookUrl' => esc_attr( MM_WPFS_EventHandler::getWebhookEndpointURL( $this->staticContext ) ),
				]
			];
			if ( ! $ctx->isStripeTax ) {
				$setupFeeParams['tax_rates'] = $options->taxRateIds;
			}

			if ( $useTestFunctions ) {
				$this->remoteRequest(
					'post',
					'/invoice_items?mode=test&accountId=' . $this->testStripeAcountId,
					$setupFeeParams
				);
			} elseif ( $useLiveFunctions ) {
				$this->remoteRequest(
					'post',
					'/invoice_items?mode=live&accountId=' . $this->liveStripeAcountId,
					$setupFeeParams
				);
			} else {
				$this->stripe->invoiceItems->create( $setupFeeParams );
			}

		}

		$hasBillingCycleAnchor = $ctx->billingCycleAnchorDay > 0;
		$hasMonthlyBillingCycleAnchor = $recurringPrice->recurring->interval === 'month' && $hasBillingCycleAnchor;
		$hasTrialPeriod = $ctx->trialPeriodDays > 0;

		$subscriptionItemsParams = [
			[
				'price' => $recurringPrice->id,
				'quantity' => $ctx->stripePlanQuantity,
			]
		];

		if ( ! $ctx->isStripeTax ) {
			$subscriptionItemsParams[0]['tax_rates'] = $options->taxRateIds;
		}

		if ( isset( $ctx->feeRecoveryLineItem ) && ! empty( $ctx->feeRecoveryLineItem ) ) {
			$subscriptionItemsParams[] = $ctx->feeRecoveryLineItem;
		}

		$subscriptionData = [
			'customer' => $stripeCustomer->id,
			'items' => $subscriptionItemsParams,
			'expand' => [
				'latest_invoice',
				'latest_invoice.payments',
				'latest_invoice.payment_intent',
				'latest_invoice.charge',
				'pending_setup_intent'
			],

		];
		if ( ! empty( $ctx->discountId ) ) {
			// it's all coupon codes now
			$subscriptionData['discounts'] = [
				[ 'coupon' => $ctx->discountId ]
			];
		}
		if ( $hasTrialPeriod ) {
			$subscriptionData['trial_period_days'] = $ctx->trialPeriodDays;
		}
		if ( $hasMonthlyBillingCycleAnchor ) {
			if ( $hasTrialPeriod ) {
				$subscriptionData['billing_cycle_anchor'] = MM_WPFS_Utils::calculateBillingCycleAnchorFromTimestamp(
					$ctx->billingCycleAnchorDay,
					MM_WPFS_Utils::calculateTrialEndFromNow( $ctx->trialPeriodDays )
				);
			} else {
				$subscriptionData['billing_cycle_anchor'] = MM_WPFS_Utils::calculateBillingCycleAnchorFromNow( $ctx->billingCycleAnchorDay );
			}

			if ( $ctx->prorateUntilAnchorDay === 1 ) {
				$subscriptionData['proration_behavior'] = 'create_prorations';
			} else {
				$subscriptionData['proration_behavior'] = 'none';
			}
		}
		if ( ! is_null( $ctx->metadata ) ) {
			$subscriptionData['metadata'] = $ctx->metadata;
		}
		if ( $ctx->isStripeTax ) {
			$subscriptionData['automatic_tax'] = [ 
				'enabled' => true
			];
		}
		$subscriptionData['metadata']['webhookUrl'] = esc_attr( MM_WPFS_EventHandler::getWebhookEndpointURL( $this->staticContext ) );

		$stripeSubscription = null;
		if ( $useTestFunctions ) {
			$subscriptionData = array_merge( $subscriptionData, [ 'validLicense' => $this->validLicense ] );
			$stripeSubscription = $this->remoteRequest(
				'post',
				'/subscriptions?mode=test&accountId=' . $this->testStripeAcountId . '&apiVersion=' . $this->userVersion,
				$subscriptionData
			);
		} elseif ( $useLiveFunctions ) {
			$subscriptionData = array_merge( $subscriptionData, [ 'validLicense' => $this->validLicense ] );
			$stripeSubscription = $this->remoteRequest(
				'post',
				'/subscriptions?mode=live&accountId=' . $this->liveStripeAcountId . '&apiVersion=' . $this->userVersion,
				$subscriptionData
			);

		} else {
			$stripeSubscription = json_decode( $this->stripe->subscriptions->create( $subscriptionData )->toJSON() );
		}

		return $stripeSubscription;
	}

	/**
	 * @throws \StripeWPFS\Stripe\Exception\ApiErrorException
	 * @throws WPFS_UserFriendlyException
	 */
	public function attachPaymentMethodToCustomer( $paymentMethod, $customerId ) {
		$params = [ 'customer' => $customerId ];

		if ( $this->apiMode === 'test' && $this->usingWpTestPlatform ) {
			$paymentMethod = $this->remoteRequest(
				'post',
				'/payment_methods/' . $paymentMethod->id . '/attach?mode=test&accountId=' . $this->testStripeAcountId,
				$params
			);
		} elseif ( $this->apiMode === 'live' && $this->usingWpLivePlatform ) {
			$paymentMethod = $this->remoteRequest(
				'post',
				'/payment_methods/' . $paymentMethod->id . '/attach?mode=live&accountId=' . $this->liveStripeAcountId,
				$params
			);
		} else {
			$this->stripe->paymentMethods->attach( $paymentMethod->id, $params );
		}

		return $paymentMethod;
	}

	/**
	 * @param $customerId
	 *
	 * @return \StripeWPFS\Stripe\Customer
	 * @throws WPFS_UserFriendlyException
	 * @throws \StripeWPFS\Stripe\Exception\ApiErrorException
	 */
	public function retrieveCustomer( $customerId ) {
		$customer = null;
		if ( $this->apiMode === 'test' && $this->usingWpTestPlatform ) {
			$customer = $this->remoteRequest(
				'get',
				'/customers/' . $customerId . '?mode=test&accountId=' . $this->testStripeAcountId
			);
		} elseif ( $this->apiMode === 'live' && $this->usingWpLivePlatform ) {
			$customer = $this->remoteRequest(
				'get',
				'/customers/' . $customerId . '?mode=live&accountId=' . $this->liveStripeAcountId
			);
		} else {
			if ( is_null( $this->stripe ) ) {
				throw new WPFS_UserFriendlyException( 'Stripe client is not initialized' );
			}
			$customer = json_decode( $this->stripe->customers->retrieve( $customerId )->toJSON() );
		}
		return $customer;
	}

	/**
	 * @param $customerId
	 * @param $params
	 *
	 * @return \StripeWPFS\Stripe\Customer
	 * @throws \StripeWPFS\Stripe\Exception\ApiErrorException
	 * @throws WPFS_UserFriendlyException
	 */
	public function retrieveCustomerWithParams( $customerId, $params ) {
		$customer = null;
		$query_string = $this->build_query( $params );

		if ( $this->apiMode === 'test' && $this->usingWpTestPlatform ) {
			$customer = $this->remoteRequest(
				'get',
				'/customers/' .$customerId . '?mode=test&accountId=' . $this->testStripeAcountId . '&' . $query_string
			);
		} elseif ( $this->apiMode === 'live' && $this->usingWpLivePlatform ) {
			$customer = $this->remoteRequest(
				'get',
				'/customers/' .$customerId . '?mode=live&accountId=' . $this->liveStripeAcountId . '&' . $query_string
			);
		} else {
			$customer = json_decode( $this->stripe->customers->retrieve( $customerId, $params )->toJSON() );
		}

		return $customer;
	}

	/**
	 * @param $productId
	 *
	 * @return \StripeWPFS\Stripe\Product
	 * @throws \StripeWPFS\Stripe\Exception\ApiErrorException
	 * @throws WPFS_UserFriendlyException
	 */
	public function retrieveProduct( $productId ) {
		$product = null;
		if ( $this->apiMode === 'test' && $this->usingWpTestPlatform ) {
			$product = $this->remoteRequest(
				'get',
				'/products/' . $productId . '?mode=test&accountId=' . $this->testStripeAcountId
			);
		} elseif ( $this->apiMode === 'live' && $this->usingWpLivePlatform ) {
			$product = $this->remoteRequest(
				'get',
				'/products/' . $productId . '?mode=live&accountId=' . $this->liveStripeAcountId
			);
		} else {
			$product = json_decode( $this->stripe->products->retrieve( $productId )->toJSON() );
		}
		return $product;
	}

	/**
	 * @param $code
	 *
	 * @return string|void
	 */
	function resolveErrorMessageByCode( $code ) {
		if ( $code === self::INVALID_NUMBER_ERROR ) {
			$resolved_message =  /* translators: message for Stripe error code 'invalid_number' */
				__( 'Your card number is invalid.', 'wp-full-stripe-free' );
		} elseif ( $code === self::INVALID_EXPIRY_MONTH_ERROR || $code === self::INVALID_NUMBER_ERROR_EXP_MONTH ) {
			$resolved_message = /* translators: message for Stripe error code 'invalid_expiry_month' */
				__( 'Your card\'s expiration month is invalid.', 'wp-full-stripe-free' );
		} elseif ( $code === self::INVALID_EXPIRY_YEAR_ERROR || $code === self::INVALID_NUMBER_ERROR_EXP_YEAR ) {
			$resolved_message = /* translators: message for Stripe error code 'invalid_expiry_year' */
				__( 'Your card\'s expiration year is invalid.', 'wp-full-stripe-free' );
		} elseif ( $code === self::INVALID_CVC_ERROR ) {
			$resolved_message = /* translators: message for Stripe error code 'invalid_cvc' */
				__( 'Your card\'s security code is invalid.', 'wp-full-stripe-free' );
		} elseif ( $code === self::INCORRECT_NUMBER_ERROR ) {
			$resolved_message = /* translators: message for Stripe error code 'incorrect_number' */
				__( 'Your card number is incorrect.', 'wp-full-stripe-free' );
		} elseif ( $code === self::EXPIRED_CARD_ERROR ) {
			$resolved_message = /* translators: message for Stripe error code 'expired_card' */
				__( 'Your card has expired.', 'wp-full-stripe-free' );
		} elseif ( $code === self::INCORRECT_CVC_ERROR ) {
			$resolved_message = /* translators: message for Stripe error code 'incorrect_cvc' */
				__( 'Your card\'s security code is incorrect.', 'wp-full-stripe-free' );
		} elseif ( $code === self::INCORRECT_ZIP_ERROR ) {
			$resolved_message = /* translators: message for Stripe error code 'incorrect_zip' */
				__( 'Your card\'s zip code failed validation.', 'wp-full-stripe-free' );
		} elseif ( $code === self::CARD_DECLINED_ERROR ) {
			$resolved_message = /* translators: message for Stripe error code 'card_declined' */
				__( 'Your card was declined.', 'wp-full-stripe-free' );
		} elseif ( $code === self::MISSING_ERROR ) {
			$resolved_message = /* translators: message for Stripe error code 'missing' */
				__( 'There is no card on a customer that is being charged.', 'wp-full-stripe-free' );
		} elseif ( $code === self::PROCESSING_ERROR ) {
			$resolved_message = /* translators: message for Stripe error code 'processing_error' */
				__( 'An error occurred while processing your card.', 'wp-full-stripe-free' );
		} elseif ( $code === self::MISSING_PAYMENT_INFORMATION ) {
			$resolved_message = /* translators: Stripe error message 'Missing payment information' */
				__( 'Missing payment information', 'wp-full-stripe-free' );
		} elseif ( $code === self::COULD_NOT_FIND_PAYMENT_INFORMATION ) {
			$resolved_message = /* translators: Stripe error message 'Could not find payment information' */
				__( 'Could not find payment information', 'wp-full-stripe-free' );
		} else {
			$resolved_message = null;
		}

		return $resolved_message;
	}

	/**
	 * @param $id
	 * @param $name
	 * @param $currency
	 * @param $interval
	 * @param $intervalCount
	 *
	 * @return \StripeWPFS\Stripe\Price
	 * @throws \StripeWPFS\Stripe\Exception\ApiErrorException
	 */
	function createRecurringPlan( $id, $name, $currency, $interval, $intervalCount, $usageType = 'licensed' ) {
		$planData = [
			"currency" => $currency,
			"unit_amount" => "1",
			"nickname" => $name,
			"recurring" => [
				"interval" => $interval,
				"interval_count" => $intervalCount,
				"usage_type" => $usageType,
			],
			"product_data" => [
				"name" => $name
			],
			"lookup_key" => $id,
		];

		$plan = null;
		if ( $this->apiMode === 'test' && $this->usingWpTestPlatform ) {
			$plan = $this->remoteRequest(
				'post',
				'/prices?mode=test&accountId=' . $this->testStripeAcountId,
				$planData
			);
		} elseif ( $this->apiMode === 'live' && $this->usingWpLivePlatform ) {
			$plan = $this->remoteRequest(
				'post',
				'/prices?mode=live&accountId=' . $this->liveStripeAcountId,
				$planData
			);
		} else {
			$plan = json_decode( $this->stripe->prices->create( $planData )->toJSON() );
		}

		return $plan;
	}

	/**
	 * Create Stripe Product for One Time & Subscription
	 * 
	 * @param $name
	 * @param $currency
	 * @param $price
	 * @param $interval
	 * 
	 * @return \StripeWPFS\Stripe\Product
	 * @throws \StripeWPFS\Stripe\Exception\ApiErrorException
	 */
	function createProduct( $name, $currency, $price, $interval = null ) {
		$currency = sanitize_text_field( $currency );
		$price    = intval( $price );
		$name     = sanitize_text_field( $name );
		$interval = sanitize_text_field( $interval );

		if ( ! in_array( $interval, [ 'day', 'week', 'month', 'year' ] ) ) {
			$interval = null;
		}

		$productData = [
			'currency'     => $currency,
			'unit_amount'  => $price,
			'product_data' => [
				'name' => $name
			],
		];

		if ( ! is_null( $interval ) ) {
			$productData['recurring'] = [
				'interval' => $interval,
			];
		}

		$product = null;
		if ( $this->apiMode === 'test' && $this->usingWpTestPlatform ) {
			$product = $this->remoteRequest(
				'post',
				'/prices?mode=test&accountId=' . $this->testStripeAcountId,
				$productData
			);
		} elseif ( $this->apiMode === 'live' && $this->usingWpLivePlatform ) {
			$product = $this->remoteRequest(
				'post',
				'/prices?mode=live&accountId=' . $this->liveStripeAcountId,
				$productData
			);
		} else {
			if ( is_null( $this->stripe ) ) {
				throw new WPFS_UserFriendlyException( 'Stripe client is not initialized' );
			}
			$product = json_decode( $this->stripe->prices->create( $productData )->toJSON() );
		}

		return $product;
	}

	/**
	 * @param $planId
	 *
	 * @return \StripeWPFS\Stripe\Price|null
	 */
	public function retrievePlan( $planId ) {
		$plan = null;
		$params = [ 'expand' => [ 'product' ] ];
		$query_string = $this->build_query( $params );

		try {
			if ( $this->apiMode === 'test' && $this->usingWpTestPlatform ) {
				$plan = $this->remoteRequest(
					'get',
					'/prices/' . $planId . '?mode=test&accountId=' . $this->testStripeAcountId . '&' . $query_string
				);
			} elseif ( $this->apiMode === 'live' && $this->usingWpLivePlatform ) {
				$plan = $this->remoteRequest(
					'get',
					'/prices/' . $planId . '?mode=live&accountId=' . $this->liveStripeAcountId . '&' . $query_string
				);
			} else {
				$plan = json_decode( $this->stripe->prices->retrieve(
					$planId,
					$params
				)->toJSON() );
			}
		} catch (Exception $e) {
			// plan not found, let's fall through
		}

		return $plan;
	}

	/**
	 * @param $planId
	 *
	 * @return \StripeWPFS\Stripe\Collection
	 * @throws \StripeWPFS\Stripe\Exception\ApiErrorException
	 * @throws WPFS_UserFriendlyException
	 */
	public function retrievePlansWithLookupKey( $planId ) {
		$prices = null;

		$params = [
			'active' => 'true',
			'lookup_keys' => [ $planId ]
		];

		$query_string = $this->build_query( $params );

		if ( $this->apiMode === 'test' && $this->usingWpTestPlatform ) {
			$prices = $this->remoteRequest(
				'get',
				'/prices?mode=test&accountId=' . $this->testStripeAcountId . '&' . $query_string
			);
		} elseif ( $this->apiMode === 'live' && $this->usingWpLivePlatform ) {
			$prices = $this->remoteRequest(
				'get',
				'/prices?mode=live&accountId=' . $this->liveStripeAcountId . '&' . $query_string
			);
		} else {
			$prices = json_decode( $this->stripe->prices->all($params )->toJSON() );
		}

		return $prices;
	}

	public function getCustomersByEmail( $email ) {
		$customers = [];

		try {
			do {
				$params = [ 'limit' => 100, 'email' => $email ];
				$last_customer = end( $customers );
				if ( $last_customer ) {
					if ( is_array( $last_customer ) )
						$params['starting_after'] = $last_customer['id'];
					else
						$params['starting_after'] = $last_customer->id;
				}
				$query_string = $this->build_query( $params );
				if ( $this->apiMode === 'test' && $this->usingWpTestPlatform ) {
					$customer_collection = $this->remoteRequest(
						'get',
						'/customers?mode=test&accountId=' . $this->testStripeAcountId . '&' . $query_string
					);
					$customers = array_merge( $customers, $customer_collection->data );
				} elseif ( $this->apiMode === 'live' && $this->usingWpLivePlatform ) {
					$customer_collection = $this->remoteRequest(
						'get',
						'/customers?mode=live&accountId=' . $this->liveStripeAcountId . '&' . $query_string
					);
					$customers = array_merge( $customers, $customer_collection->data );
				} else {
					if ( is_null( $this->stripe ) ) {
						throw new WPFS_UserFriendlyException( 'Stripe client is not initialized' );
					}
					$customer_collection = $this->stripe->customers->all( $params );
					$customers = array_merge( $customers, $customer_collection->data );
				}
			} while ( $customer_collection->has_more );
		} catch (Exception $ex) {
			$this->logger->error( __FUNCTION__, 'Error while getting customers by email', $ex );

			$customers = [];
		}

		return $customers;
	}

	/**
	 * @param $params
	 * @return \StripeWPFS\Stripe\Collection
	 * @throws \StripeWPFS\Stripe\Exception\ApiErrorException
	 * @throws WPFS_UserFriendlyException
	 */
	public function getCustomersWithParams( $params ) {
		$customers = null;
		$query_string = $this->build_query( $params );

		if ( $this->apiMode === 'test' && $this->usingWpTestPlatform ) {
			$customers = $this->remoteRequest(
				'get',
				'/customers?mode=test&accountId=' . $this->testStripeAcountId . '&' . $query_string
			);
			$customers = $customers->data;
		} elseif ( $this->apiMode === 'live' && $this->usingWpLivePlatform ) {
			$customers = $this->remoteRequest(
				'get',
				'/customers?mode=live&accountId=' . $this->liveStripeAcountId . '&' . $query_string
			);
			$customers = $customers->data;
		} else {
			$customers = json_decode( $this->stripe->customers->all( $params )->toJSON() );
		}
		return $customers;
	}

	/**
	 * @param $customerId
	 * @param $params
	 * @return array
	 * @throws \StripeWPFS\Stripe\Exception\ApiErrorException
	 * @throws WPFS_UserFriendlyException
	 */
	public function createCustomerPortalSession( $customerId, $returnUrl ) {
		$session = null;
		if ( $this->apiMode === 'test' && $this->usingWpTestPlatform ) {
			$session = $this->remoteRequest(
				'post',
				'/billing/portal?mode=test&accountId=' . $this->testStripeAcountId,
				[
					'customer' => $customerId,
					'return_url' => $returnUrl
				]
			);
		} elseif ( $this->apiMode === 'live' && $this->usingWpLivePlatform ) {
			$session = $this->remoteRequest(
				'post',
				'/billing/portal?mode=live&accountId=' . $this->liveStripeAcountId,
				[
					'customer' => $customerId,
					'return_url' => $returnUrl
				]
			);
		} else {
			$session = json_decode( $this->stripe->billingPortal->sessions->create( [ 'customer' => $customerId ] )->toJSON() );
		}

		return $session;
	}

	/**
	 * @param $current_url
	 * @return mixed|null
	 * @throws WPFS_UserFriendlyException
	 */
	public function getAccountLink( $current_url, $mode = 'test' ) {
		$accountLink = null;
		if ( $mode === 'test' ) {
			$accountLink = $this->remoteRequest(
				'post',
				'/account/link?mode=test',
				[
					'return_url' => $current_url,
				]
			);
		} elseif ( $mode === 'live' ) {
			$accountLink = $this->remoteRequest(
				'post',
				'/account/link?mode=live',
				[
					'return_url' => $current_url,
				]
			);
		}

		return $accountLink;
	}

	/**
	 * @param $accountId
	 * @return mixed
	 * @throws WPFS_UserFriendlyException
	 */
	public function getTestAccount( $accountId ) {
		try {
			return $this->remoteRequest( 'get', '/account?mode=test&accountId=' . $accountId );
		} catch ( Exception $ex ) {
			$this->logger->error( __FUNCTION__, 'Error while getting test account', $ex );
			throw new WPFS_UserFriendlyException( 'Error while getting test account: ' . $ex->getMessage() );
		}
	}

	/**
	 * @param $accountId
	 * @throws WPFS_UserFriendlyException
	 */
	public function getLiveAccount( $accountId ) {
		try {
			return $this->remoteRequest( 'get', '/account?mode=live&accountId=' . $accountId );
		} catch ( Exception $ex ) {
			$this->logger->error( __FUNCTION__, 'Error while getting live account', $ex );
			throw new WPFS_UserFriendlyException( 'Error while getting live account: ' . $ex->getMessage() );
		}
	}

	/**
	 * @return array|\StripeWPFS\Stripe\Collection
	 */
	public function getSubscriptionPlans() {
		$plans = [];
		$params = [
			'type' => 'recurring'
		];
		try {
			$plans = $this->getPriceList( $params );
		} catch (Exception $ex) {
			$this->logger->error( __FUNCTION__, 'Error while getting subscription plans', $ex );

			$plans = [];
		}

		return $plans;
	}

	private function getPriceList( $params ) {
		$params['limit'] = 100;
		$params['expand'] = [ 'data.product' ];
		$prices = [];
		do {
			$lastPrice = end( $prices );
			if ( $lastPrice ) {
				if ( isset( $lastPrice ) && is_array( $lastPrice ) )
					$params['starting_after'] = ( isset( $lastPrice['id'] ) && ! empty( $lastPrice['id'] ) ) ? $lastPrice['id'] : null;
				else
					$params['starting_after'] = $lastPrice->id ? $lastPrice->id : null;
			}
			$priceCollection = null;
			$query_string = $this->build_query( $params );
			if ( $this->apiMode === 'test' && $this->usingWpTestPlatform ) {
				$priceCollection = $this->remoteRequest(
					'get',
					'/prices?mode=test&accountId=' . $this->testStripeAcountId . '&' . $query_string
				);
				$prices = array_merge( $prices, $priceCollection->data );
			} elseif ( $this->apiMode === 'live' && $this->usingWpLivePlatform ) {
				$priceCollection = $this->remoteRequest(
					'get',
					'/prices?mode=live&accountId=' . $this->liveStripeAcountId . '&' . $query_string
				);
				$prices = array_merge( $prices, $priceCollection->data );
			} else {
				$priceCollection = $this->stripe->prices->all( $params );
				$prices = array_merge( $prices, $priceCollection['data'] );
			}
		} while ( $priceCollection->has_more );

		return $prices;
	}

	/**
	 * @return array|\StripeWPFS\Stripe\Collection
	 * @throws WPFS_UserFriendlyException
	 */
	public function getOnetimePrices() {
		$prices = [];
		$params = [
			'active' => 'true',
			'type' => 'one_time'
		];
		try {
			$prices = $this->getPriceList( $params );
		} catch (Exception $ex) {
			$this->logger->error( __FUNCTION__, 'Error while getting one-time prices', $ex );
			$prices = [];
		}
		return $prices;
	}

	/**
	 * @return array|\StripeWPFS\Stripe\Collection
	 */
	public function getRecurringPrices() {
		$prices = [];
		$params = [
			'active' => 'true',
			'type' => 'recurring'
		];
		try {
			$prices = $this->getPriceList( $params );
		} catch (Exception $ex) {
			$this->logger->error( __FUNCTION__, 'Error while getting recurring prices', $ex );
			$prices = [];
		}
		return $prices;
	}

	/**
	 * @return array|\StripeWPFS\Stripe\Collection
	 * @throws WPFS_UserFriendlyException
	 */
	public function getTaxRates() {
		$taxRates = [];
		do {
			$params = [
				'active' => 'true',
				'limit' => 100,
			];

			$lastTaxRate = end( $taxRates );
			if ( $lastTaxRate ) {
				if ( is_array( $lastTaxRate ) )
					$params['starting_after'] = $lastTaxRate['id'];
				else
					$params['starting_after'] = $lastTaxRate->id;
			}
			$taxRateCollection = null;
			$query_string = $this->build_query( $params );
			if ( $this->apiMode === 'test' && $this->usingWpTestPlatform ) {
				$taxRateCollection = $this->remoteRequest(
					'get',
					'/tax_rates?mode=test&accountId=' . $this->testStripeAcountId . '&' . $query_string
				);
				$taxRates = array_merge( $taxRates, $taxRateCollection->data );
			} elseif ( $this->apiMode === 'live' && $this->usingWpLivePlatform ) {
				$taxRateCollection = $this->remoteRequest(
					'get',
					'/tax_rates?mode=live&accountId=' . $this->liveStripeAcountId . '&' . $query_string
				);
				$taxRates = array_merge( $taxRates, $taxRateCollection->data );
			} else {
				if ( is_null( $this->stripe ) ) {
					throw new WPFS_UserFriendlyException( 'Stripe client is not initialized' );
				}
				$taxRateCollection = json_decode( $this->stripe->taxRates->all( $params )->toJSON() );
				$taxRates = array_merge( $taxRates, $taxRateCollection->data );
			}
		} while ( $taxRateCollection->has_more );

		return $taxRates;
	}

	/**
	 * @param $code
	 *
	 * @return \StripeWPFS\Stripe\Coupon
	 * @throws \StripeWPFS\Stripe\Exception\ApiErrorException
	 * @throws WPFS_UserFriendlyException
	 */
	public function retrieveCoupon( $code ) {
		$coupons = null;
		$params = [ 'expand' => [ 'applies_to' ] ];
		$query_string = $this->build_query( $params );

		if ( $this->apiMode === 'test' && $this->usingWpTestPlatform ) {
			$coupons = $this->remoteRequest(
				'get',
				'/coupons/' . $code . '?mode=test&accountId=' . $this->testStripeAcountId . '&' . $query_string
			);
		} elseif ( $this->apiMode === 'live' && $this->usingWpLivePlatform ) {
			$coupons = $this->remoteRequest(
				'get',
				'/coupons/' . $code . '?mode=live&accountId=' . $this->liveStripeAcountId . '&' . $query_string
			);
		} else {
			$coupons = json_decode( $this->stripe->coupons->retrieve( $code, $params )->toJSON() );
		}
		return $coupons;
	}

	/**
	 * @param $code
	 *
	 * @return \StripeWPFS\Stripe\PromotionCode
	 * @throws \StripeWPFS\Stripe\Exception\ApiErrorException
	 * @throws WPFS_UserFriendlyException
	 */
	public function retrievePromotionalCode( $code ) {
		$promotionalCodesCollection = null;
		$params = [
			'code' => $code,
			'expand' => [ 'data.coupon.applies_to' ]
		];
		$query_string = $this->build_query( $params );

		if ( $this->apiMode === 'test' && $this->usingWpTestPlatform ) {
			$promotionalCodesCollection = $this->remoteRequest(
				'get',
				'/promotion_codes?mode=test&accountId=' . $this->testStripeAcountId . '&' . $query_string
			);
		} elseif ( $this->apiMode === 'live' && $this->usingWpLivePlatform ) {
			$promotionalCodesCollection = $this->remoteRequest(
				'get',
				'/promotion_codes?mode=live&accountId=' . $this->liveStripeAcountId . '&' . $query_string
			);
		} else {
			$promotionalCodesCollection = $this->stripe->promotionCodes->all( $params );
		}

		$result = null;

		// TODO: replace this with something else that doesn't require the stripe specific object
		$promotionalCodesCollection = \StripeWPFS\Stripe\Collection::constructFrom(
			json_decode(
				json_encode( $promotionalCodesCollection ),
				true
			)
		);

		foreach ( $promotionalCodesCollection->autoPagingIterator() as $promotionCode ) {
			if ( strcasecmp( $code, $promotionCode->code ) === 0 ) {
				$result = $promotionCode;
				break;
			}
		}

		return $result;
	}

	protected function getPromotionalCode( $code ) {
		try {
			return $this->retrievePromotionalCode( $code );
		} catch (Exception $ex) {
			$this->logger->debug( __FUNCTION__, "Cannot retrieve promotional code" . $ex );
			return null;
		}
	}

	protected function getCoupon( $code ) {
		try {
			return $this->retrieveCoupon( $code );
		} catch (Exception $ex) {
			$this->logger->debug( __FUNCTION__, "Cannot retrieve coupon" . $ex );

			return null;
		}
	}

	/**
	 * @param $code string
	 * @return \StripeWPFS\Stripe\Coupon|null
	 */
	public function retrieveCouponByPromotionalCodeOrCouponCode( $code ) {
		$result = null;

		try {
			$promotionalCode = $this->getPromotionalCode( $code );

			if ( ! is_null( $promotionalCode ) ) {
				if ( false == $promotionalCode->active ) {
					$result = $this->getCoupon( $code );
				} else {
					$result = $promotionalCode->coupon;
				}
			} else {
				$result = $this->getCoupon( $code );
			}
		} catch (Exception $ex) {
			$this->logger->error( __FUNCTION__, "Cannot retrieve coupon or promotional code", $ex );
		}

		return $result;
	}


	/**
	 * @param $invoiceId
	 *
	 * @return \StripeWPFS\Stripe\Invoice
	 * @throws WPFS_UserFriendlyException
	 * @throws \StripeWPFS\Stripe\Exception\ApiErrorException
	 */
	function retrieveInvoice( $invoiceId ) {
		$invoice = null;
		if ( $this->apiMode === 'test' && $this->usingWpTestPlatform ) {
			$invoice = $this->remoteRequest(
				'get',
				'/invoices/' . $invoiceId . '?mode=test&accountId=' . $this->testStripeAcountId
			);
		} elseif ( $this->apiMode === 'live' && $this->usingWpLivePlatform ) {
			$invoice = $this->remoteRequest(
				'get',
				'/invoices/' . $invoiceId . '?mode=live&accountId=' . $this->liveStripeAcountId
			);
		} else {
			$invoice = json_decode( $this->stripe->invoices->retrieve( $invoiceId )->toJSON() );
		}

		return $invoice;
	}

	/**
	 * @param $paymentMethodId
	 * @param $customerName
	 * @param $customerEmail
	 * @param $metadata
	 *
	 * @return \StripeWPFS\Stripe\Customer
	 *
	 * @throws Stripe\Exception\ApiErrorException
	 * @throws WPFS_UserFriendlyException
	 */
	function createCustomerWithPaymentMethod(
		$paymentMethodId,
		$customerName,
		$customerEmail,
		$metadata,
		$taxIdType = null,
		$taxId = null,
		$billing_address = null,
		$billing_name = null,
		$shipping_address = null,
		$shipping_name = null
	) {
		$customer = [
			'email' => $customerEmail,
		];
		if ( ! is_null( $paymentMethodId ) ) {
			$customer['payment_method'] = $paymentMethodId;
			$customer['invoice_settings']['default_payment_method'] = $paymentMethodId;
		}

		if ( ! is_null( $billing_name ) ) {
			$customer['name'] = $billing_name;
		} elseif ( ! is_null( $customerName ) ) {
			$customer['name'] = $customerName;
		}

		if ( ! is_null( $metadata ) ) {
			$customer['metadata'] = $metadata;
		}

		if ( ! empty( $taxIdType ) && ! empty( $taxId ) ) {
			$customer['tax_id_data'] = [ 
				[ 
					'type' => $taxIdType,
					'value' => $taxId
				]
			];
		}

		if ( ! empty( $billing_address ) ) {
			$customer['address'] = MM_WPFS_Utils::prepareStripeBillingAddressHashFromArray( $billing_address );
		}
		if ( ! empty( $shipping_address ) ) {
			$customer['shipping'] = [ 
				'address' => MM_WPFS_Utils::prepareStripeBillingAddressHashFromArray( $shipping_address ),
				'name' => $shipping_name
			];
		}

		$createdCustomer = null;
		if ( $this->apiMode === 'test' && $this->usingWpTestPlatform ) {
			$createdCustomer = $this->remoteRequest(
				'post',
				'/customers?mode=test&accountId=' . $this->testStripeAcountId,
				$customer
			);
		} elseif ( $this->apiMode === 'live' && $this->usingWpLivePlatform ) {
			$createdCustomer = $this->remoteRequest(
				'post',
				'/customers?mode=live&accountId=' . $this->liveStripeAcountId,
				$customer
			);
		} else {
			$createdCustomer = json_decode( $this->stripe->customers->create( $customer )->toJSON() );
		}

		return $createdCustomer;
	}

	/**
	 * @param $paymentMethodId
	 * @param $customerId
	 * @param $currency
	 * @param $amount
	 * @param $capture
	 * @param $description
	 * @param $metadata - optional default null
	 * @param $stripeEmail - optional default null
	 * @param $allowRedirects - 'never' or 'always' defaults to 'never'
	 * @param $paymentMethodTypes - list of payment methods to allow defaults to ['card','link']
	 *
	 * @return stdClass
	 *
	 * @throws Stripe\Exception\ApiErrorException
	 * @throws WPFS_UserFriendlyException
	 */
	function createPaymentIntent(
		$paymentMethodId,
		$customerId,
		$currency,
		$amount,
		$capture,
		$description,
		$metadata = null,
		$stripeEmail = null,
		$allowRedirects = 'never',
		$paymentMethodTypes = [ 'card', 'link' ]
	) {
		$paymentIntentParameters = [
			'amount' => isset( $amount ) && ! empty( $amount ) ? $amount : 100,
			'currency' => $currency,
			'expand' => [ 'latest_charge' ],
		];
		if ( ! empty( $customerId ) ) {
			$paymentIntentParameters['customer'] = $customerId;
		}
		if ( ! empty( $paymentMethodId ) ) {
			$paymentIntentParameters['payment_method'] = $paymentMethodId;
		}
		if ( ! empty( $description ) ) {
			$paymentIntentParameters['description'] = $description;
		}
		if ( false === $capture ) {
			$paymentIntentParameters['capture_method'] = 'manual';
		}
		if ( isset( $stripeEmail ) ) {
			$paymentIntentParameters['receipt_email'] = $stripeEmail;
		}
		if ( isset( $metadata ) ) {
			$paymentIntentParameters['metadata'] = $metadata;
		}
		if ( isset( $paymentMethodTypes ) ) {
			if ( ! is_array( $paymentMethodTypes ) )
				$paymentIntentParameters['payment_method_types'] = json_decode( $paymentMethodTypes );
			else
				$paymentIntentParameters['payment_method_types'] = $paymentMethodTypes;
		} else {
			$paymentIntentParameters['automatic_payment_methods'] = [ 
				'enabled' => true,
				'allow_redirects' => $allowRedirects, // payment intents can be redirected e.g. for BNPL type payments so 
			];
			$paymentIntentParameters['confirm'] = true;
		}

		if ( $this->apiMode === 'test' && $this->usingWpTestPlatform ) {
			$paymentIntentParameters = array_merge(
				$paymentIntentParameters,
				[
					'validLicense' => $this->validLicense,
				]
			);
			$intent = $this->remoteRequest(
				'post',
				'/payment_intents?mode=test&accountId=' . $this->testStripeAcountId . '&apiVersion=' . $this->userVersion,
				apply_filters( 'fullstripe_payment_intent_parameters', $paymentIntentParameters )
			);
		} elseif ( $this->apiMode === 'live' && $this->usingWpLivePlatform ) {
			$paymentIntentParameters = array_merge(
				$paymentIntentParameters,
				[
					'validLicense' => $this->validLicense,
				]
			);
			$intent = $this->remoteRequest(
				'post',
				'/payment_intents?mode=live&accountId=' . $this->liveStripeAcountId . '&apiVersion=' . $this->userVersion,
				apply_filters( 'fullstripe_payment_intent_parameters', $paymentIntentParameters )
			);
		} else {
			$paymentIntentParameters['automatic_payment_methods']['enabled'] = false;
			$intent = json_decode( $this->stripe->paymentIntents->create(
				apply_filters(
					'fullstripe_payment_intent_parameters',
					$paymentIntentParameters
				)
			)->toJSON() );
		}

		return $intent;
	}

	/**
	 * @throws WPFS_UserFriendlyException
	 */
	function getTestAccountLink( $accountId, $refreshUrl, $returnUrl ) {
		$params = [
			'refresh_url' => $refreshUrl,
			'return_url' => $returnUrl,
		];

		$data = $this->remoteRequest(
			'post',
			'/account/onboarding?mode=test&accountId=' . $accountId,
			$params
		);

		return $data->url;
	}

	/**
	 * @throws WPFS_UserFriendlyException
	 */
	function getLiveAccountLink( $accountId, $refreshUrl, $returnUrl ) {
		$params = [
			'refresh_url' => $refreshUrl,
			'return_url' => $returnUrl,
		];

		$data = $this->remoteRequest(
			'post',
			'/account/onboarding?mode=live&accountId=' . $accountId,
			$params
		);

		return $data->url;
	}

	/**
	 * @param $ctx MM_WPFS_CreateOneTimeInvoiceContext
	 * @param $options MM_WPFS_CreateOneTimeInvoiceOptions
	 * @return \StripeWPFS\Stripe\Invoice
	 * @throws \StripeWPFS\Stripe\Exception\ApiErrorException
	 * @throws WPFS_UserFriendlyException
	 */
	function createInvoiceForOneTimePayment( $ctx, $options ) {
		$invoiceParams = [
			'customer' => $ctx->stripeCustomerId,
			'auto_advance' => $options->autoAdvance,
			'metadata' => [
				'webhookUrl' => esc_attr( MM_WPFS_EventHandler::getWebhookEndpointURL( $this->staticContext ) ),
			]
		];

		// if we're not using connect, don't send the amount as it's not allowed by Stripe in the direct call
		// we use the amount in the cloud functions to calculate the application fee that is added to the invoice
		if ( $this->usingWpTestPlatform || $this->usingWpLivePlatform ) {
			$invoiceParams['amount'] = $ctx->amount;
		}

		$invoiceItemParams = [
			'customer' => $ctx->stripeCustomerId,
		];

		if ( $ctx->stripePriceId !== null ) {
			$invoiceItemParams['pricing'] = [
				'price' => $ctx->stripePriceId,
			];
		} else {
			$invoiceItemParams['amount'] = $ctx->amount;
			$invoiceItemParams['currency'] = $ctx->currency;
			$invoiceItemParams['description'] = $ctx->productName;
		}

		if ( isset( $ctx->stripeCouponId ) ) {
			$invoiceItemParams['discounts'] = [
				[ 'coupon' => $ctx->stripeCouponId ]
			];
		}

		if ( $ctx->isStripeTax ) {
			$invoiceParams['automatic_tax'] = [ 
				'enabled' => true
			];
		} else {
			if ( isset( $options->taxRateIds ) && count( $options->taxRateIds ) > 0 ) {
				$invoiceItemParams['tax_rates'] = $options->taxRateIds;
			}
		}

		$createdInvoice = null;

		if ( $this->apiMode === 'test' && $this->usingWpTestPlatform ) {
			// add the license key to the invoice params
			$invoiceParams = array_merge( $invoiceParams, [ 'validLicense' => $this->validLicense ] );
			// create invoice
			$createdInvoice = $this->remoteRequest(
				'post',
				'/invoices?mode=test&accountId=' . $this->testStripeAcountId . '&apiVersion=' . $this->userVersion,
				$invoiceParams
			);
			$invoiceItemParams['invoice'] = $createdInvoice->id;

			// create invoice item and attach to newly created invoice
			$this->remoteRequest(
				'post',
				'/invoice_items?mode=test&accountId=' . $this->testStripeAcountId,
				$invoiceItemParams
			);
		} elseif ( $this->apiMode === 'live' && $this->usingWpLivePlatform ) {
			// add the license key to the invoice params
			$invoiceParams = array_merge( $invoiceParams, [ 'validLicense' => $this->validLicense ] );
			// create invoice
			$createdInvoice = $this->remoteRequest(
				'post',
				'/invoices?mode=live&accountId=' . $this->liveStripeAcountId . '&apiVersion=' . $this->userVersion,
				$invoiceParams
			);
			$invoiceItemParams['invoice'] = $createdInvoice->id;

			// create invoice item and attach to newly created invoice
			$this->remoteRequest(
				'post',
				'/invoice_items?mode=live&accountId=' . $this->liveStripeAcountId,
				$invoiceItemParams
			);
		} else {
			// We're doing it the old fashioned way; invoice item first and invoice second. The invoice item is attached to the invoice in the process.
			$invoiceItem = json_decode( $this->stripe->invoiceItems->create( $invoiceItemParams )->toJSON() );
			$createdInvoice = json_decode( $this->stripe->invoices->create( $invoiceParams )->toJSON() );
		}

		return $createdInvoice;
	}

	/**
	 * @param $ctx MM_WPFS_CreateOneTimeInvoiceContext
	 * @param $options MM_WPFS_CreateOneTimeInvoiceOptions
	 * @return \StripeWPFS\Stripe\Invoice
	 */
	function createPreviewInvoiceForOneTimePayment( $ctx, $options ) {
		$invoiceParams = [];

		$address = [ 
			'country' => $ctx->taxCountry
		];
		if ( $ctx->isStripeTax ) {
			$invoiceParams['automatic_tax'] = [ 
				'enabled' => true
			];

			if ( ! empty( $ctx->taxPostalCode ) ) {
				$address['postal_code'] = $ctx->taxPostalCode;
			}
		} else {
			if ( ! empty( $ctx->taxState ) ) {
				$address['state'] = $ctx->taxState;
			}
		}
		$invoiceParams['customer_details'] = [ 
			'address' => $address
		];

		if ( $ctx->isStripeTax && ! empty( $ctx->taxIdType ) && ! empty( $ctx->taxId ) ) {
			$invoiceParams['customer_details']['tax_ids'] = [ 
				[ 
					'type' => $ctx->taxIdType,
					'value' => $ctx->taxId,
				]
			];
		}

		$itemParams = [];
		if ( $ctx->stripePriceId !== null ) {
			$itemParams['price'] = $ctx->stripePriceId;
		} else {
			$itemParams['amount'] = round( $ctx->amount );
			$itemParams['currency'] = $ctx->currency;
			$itemParams['description'] = $ctx->productName;
		}

		if ( isset( $ctx->stripeCouponId ) ) {
			$itemParams['discounts'] = [
				[ 'coupon' => $ctx->stripeCouponId ]
			];
		}

		if ( ! $ctx->isStripeTax ) {
			if ( isset( $options->taxRateIds ) && count( $options->taxRateIds ) > 0 ) {
				$itemParams['tax_rates'] = $options->taxRateIds;
			}
		}

		$invoiceParams['invoice_items'] = [ 
			$itemParams
		];

		return $this->getUpcomingInvoice( $invoiceParams );
	}

	/**
	 * @param $finalizedInvoice
	 * @param $stripePaymentMethodId
	 * @param $stripeChargeDescription
	 * @param $stripeReceiptEmailAddress
	 *
	 * @return \StripeWPFS\Stripe\Invoice
	 * @throws \StripeWPFS\Stripe\Exception\ApiErrorException
	 */
	function updatePaymentIntentByInvoice(
		$finalizedInvoice,
		$stripePaymentMethodId,
		$stripeChargeDescription,
		$metadata,
		$stripeReceiptEmailAddress
	) {
		$paymentIntentParameters = [];
		if ( ! empty( $stripeChargeDescription ) ) {
			$paymentIntentParameters['description'] = $stripeChargeDescription;
		}
		if ( isset( $stripeReceiptEmailAddress ) ) {
			$paymentIntentParameters['receipt_email'] = $stripeReceiptEmailAddress;
		}
		if ( isset( $metadata ) ) {
			$paymentIntentParameters['metadata'] = $metadata;
		}

		$generatedPaymentIntent = null;
		$updatedPaymentIntent = null;
		$updatedInvoice = null;

		$payments = $finalizedInvoice->payments->data ?? [];
		$stripePaymentIntent = null;

		foreach ( $payments as $payment ) {
			if (
				isset( $payment->payment->type ) &&
				$payment->payment->type === 'payment_intent' &&
				isset( $payment->payment->payment_intent )
			) {
				$stripePaymentIntent = $payment->payment->payment_intent;
				break;
			}
		}

		if ( $this->apiMode === 'test' && $this->usingWpTestPlatform ) {
			$generatedPaymentIntent = $this->remoteRequest(
				'get',
				'/payment_intents/' . $stripePaymentIntent. '?mode=test&accountId=' . $this->testStripeAcountId
			);

			$updatedPaymentIntent = $this->remoteRequest(
				'post',
				'/payment_intents/' . $generatedPaymentIntent->id . '?mode=test&accountId=' . $this->testStripeAcountId . '&apiVersion=' . $this->userVersion,
				apply_filters( 'fullstripe_payment_intent_parameters', $paymentIntentParameters )
			);

			$this->remoteRequest(
				'post',
				'/payment_intents/' . $updatedPaymentIntent->id . '/confirm?mode=test&accountId=' . $this->testStripeAcountId,
				[ 'payment_method' => $stripePaymentMethodId ]
			);

			$updatedInvoice = $this->remoteRequest(
				'post',
				'/invoices/' . $finalizedInvoice->id . '?mode=test&accountId=' . $this->testStripeAcountId,
				[]
			);
		} elseif ( $this->apiMode === 'live' && $this->usingWpLivePlatform ) {
			$generatedPaymentIntent = $this->remoteRequest(
				'get',
				'/payment_intents/' . $stripePaymentIntent. '?mode=live&accountId=' . $this->liveStripeAcountId
			);

			$updatedPaymentIntent = $this->remoteRequest(
				'post',
				'/payment_intents/' . $generatedPaymentIntent->id . '?mode=live&accountId=' . $this->liveStripeAcountId . '&apiVersion=' . $this->userVersion,
				apply_filters( 'fullstripe_payment_intent_parameters', $paymentIntentParameters )
			);

			$this->remoteRequest(
				'post',
				'/payment_intents/' . $updatedPaymentIntent->id . '/confirm?mode=live&accountId=' . $this->liveStripeAcountId,
				[ 'payment_method' => $stripePaymentMethodId ]
			);

			$updatedInvoice = $this->remoteRequest(
				'post',
				'/invoices/' . $finalizedInvoice->id . '?mode=live&accountId=' . $this->liveStripeAcountId,
				[]
			);
		} else {
			$generatedPaymentIntent = json_decode( $this->stripe->paymentIntents->retrieve( $stripePaymentIntent )->toJSON() );
			$updatedPaymentIntent = json_decode( $this->stripe->paymentIntents->update(
				$generatedPaymentIntent->id,
				apply_filters( 'fullstripe_payment_intent_parameters', $paymentIntentParameters )
			)->toJSON() );
			$this->stripe->paymentIntents->confirm(
				$updatedPaymentIntent->id,
				[ 'payment_method' => $stripePaymentMethodId ]
			);
			$updatedInvoice = json_decode( $this->stripe->invoices->update( $finalizedInvoice->id )->toJSON() );
		}

		return $updatedInvoice;
	}

	/**
	 * @param $paymentIntentId
	 *
	 * @return \StripeWPFS\Stripe\PaymentIntent
	 * @throws \StripeWPFS\Stripe\Exception\ApiErrorException
	 * @throws WPFS_UserFriendlyException
	 */
	function retrievePaymentIntent( $paymentIntentId ) {
		$intent = null;
		if ( $this->apiMode === 'test' && $this->usingWpTestPlatform ) {
			$intent = $this->remoteRequest(
				'get',
				'/payment_intents/' . $paymentIntentId . '?mode=test&accountId=' . $this->testStripeAcountId . '&expand[]=latest_charge'
			);
		} elseif ( $this->apiMode === 'live' && $this->usingWpLivePlatform ) {
			$intent = $this->remoteRequest(
				'get',
				'/payment_intents/' . $paymentIntentId . '?mode=live&accountId=' . $this->liveStripeAcountId . '&expand[]=latest_charge'
			);
		} else {
			$intent = json_decode( $this->stripe->paymentIntents->retrieve( $paymentIntentId )->toJSON() );
		}


		return $intent;
	}

	/**
	 * @param $invoiceId
	 * @param $params
	 *
	 * @return \StripeWPFS\Stripe\Invoice
	 * @throws \StripeWPFS\Stripe\Exception\ApiErrorException
	 */
	public function retrieveInvoiceWithParams( $invoiceId, $params ) {
		$invoice = null;
		$query_string = $this->build_query( $params );

		if ( $this->apiMode === 'test' && $this->usingWpTestPlatform ) {
			$invoice = $this->remoteRequest(
				'get',
				'/invoices/' . $invoiceId . '?mode=test&accountId=' . $this->testStripeAcountId . '&' . $query_string
			);
		} elseif ( $this->apiMode === 'live' && $this->usingWpLivePlatform ) {
			$invoice = $this->remoteRequest(
				'get',
				'/invoices/' . $invoiceId . '?mode=live&accountId=' . $this->liveStripeAcountId . '&' . $query_string
			);
		} else {
			$invoice = json_decode( $this->stripe->invoices->retrieve( $invoiceId, $params )->toJSON() );
		}

		return $invoice;
	}

	/**
	 * @param $sessionId
	 * @param $params
	 *
	 * @return \StripeWPFS\Stripe\Checkout\Session
	 * @throws \StripeWPFS\Stripe\Exception\ApiErrorException
	 * @throws WPFS_UserFriendlyException
	 */
	public function retrieveCheckoutSessionWithParams( $sessionId, $params ) {
		$checkoutSession = null;
		$query_string = $this->build_query( $params );
		if ( $this->apiMode === 'test' && $this->usingWpTestPlatform ) {
			$checkoutSession = $this->remoteRequest(
				'get',
				'/checkout/' .$sessionId . '?mode=test&accountId=' . $this->testStripeAcountId . '&' . $query_string
			);
		} elseif ( $this->apiMode === 'live' && $this->usingWpLivePlatform ) {
			$checkoutSession = $this->remoteRequest(
				'get',
				'/checkout/' .$sessionId . '?mode=live&accountId=' . $this->liveStripeAcountId . '&' . $query_string
			);
		} else {
			$checkoutSession = json_decode( $this->stripe->checkout->sessions->retrieve( $sessionId, $params )->toJSON() );
		}

		return $checkoutSession;
	}

	/**
	 * @param $paymentMethodId
	 *
	 * @return \StripeWPFS\Stripe\PaymentMethod
	 * @throws \StripeWPFS\Stripe\Exception\ApiErrorException
	 * @throws WPFS_UserFriendlyException
	 */
	public function retrievePaymentMethod( $paymentMethodId ) {
		$paymentMethod = null;
		if ( $this->apiMode === 'test' && $this->usingWpTestPlatform ) {
			$paymentMethod = $this->remoteRequest(
				'get',
				'/payment_methods/' . $paymentMethodId . '?mode=test&accountId=' . $this->testStripeAcountId
			);
		} elseif ( $this->apiMode === 'live' && $this->usingWpLivePlatform ) {
			$paymentMethod = $this->remoteRequest(
				'get',
				'/payment_methods/' . $paymentMethodId . '?mode=live&accountId=' . $this->liveStripeAcountId
			);
		} else {
			$paymentMethod = json_decode( $this->stripe->paymentMethods->retrieve( $paymentMethodId )->toJSON() );
		}

		return $paymentMethod;
	}

	/**
	 * @param $eventID
	 *
	 * @return \StripeWPFS\Stripe\Event
	 * @throws \StripeWPFS\Stripe\Exception\ApiErrorException
	 * @throws WPFS_UserFriendlyException
	 */
	public function retrieveEvent( $eventID ) {
		$event = null;
		if ( $this->apiMode === 'test' && $this->usingWpTestPlatform ) {
			$event = $this->remoteRequest(
				'get',
				'/events/' . $eventID . '?mode=test&accountId=' . $this->testStripeAcountId
			);
		} elseif ( $this->apiMode === 'live' && $this->usingWpLivePlatform ) {
			$event = $this->remoteRequest(
				'get',
				'/events/' . $eventID . '?mode=live&accountId=' . $this->liveStripeAcountId
			);
		} else {
			$event = json_decode( $this->stripe->events->retrieve( $eventID )->toJSON() );
		}

		return $event;
	}

	/**
	 * @return \StripeWPFS\Stripe\Invoice
	 * @throws \StripeWPFS\Stripe\Exception\ApiErrorException
	 * @throws WPFS_UserFriendlyException
	 */
	public function createSetupIntent() {
		$params = [
			'usage' => 'off_session',
			'metadata' => [
				'webhookUrl' => esc_attr( MM_WPFS_EventHandler::getWebhookEndpointURL( $this->staticContext ) ),
			],
			'automatic_payment_methods' => [
				'enabled' => true,
				'allow_redirects' => 'never', //setup intents do not support redirects
			],
		];

		$setupIntent = null;
		if ( ( $this->apiMode === 'test' && $this->usingWpTestPlatform ) || ( $this->apiMode === 'live' && $this->usingWpLivePlatform ) ) {
			$accountId = $this->apiMode === 'test' ? $this->testStripeAcountId : $this->liveStripeAcountId;
			$setupIntent = $this->remoteRequest(
				'post',
				'/setup_intents?mode=' . $this->apiMode . '&accountId=' . $accountId,
				$params
			);
		} else {
			// We have to use these for non-connect accounts
			unset( $params['automatic_payment_methods'] );
			$params['payment_method_types'] = [ 'card', 'link' ];

			$setupIntent = json_decode( $this->stripe->setupIntents->create( $params )->toJSON() );
		}

		return $setupIntent;
	}


	/**
	 * @param $stripePaymentMethodId
	 *
	 * @return \StripeWPFS\Stripe\SetupIntent
	 * @throws \StripeWPFS\Stripe\Exception\ApiErrorException
	 * @throws WPFS_UserFriendlyException
	 */
	public function createSetupIntentWithPaymentMethod( $stripePaymentMethodId ) {
		$params = [
			'usage' => 'off_session',
			'payment_method' => $stripePaymentMethodId,
			'confirm' => false,
			'metadata' => [
				'webhookUrl' => esc_attr( MM_WPFS_EventHandler::getWebhookEndpointURL( $this->staticContext ) ),
			],
			'automatic_payment_methods' => [
				'enabled' => true,
				'allow_redirects' => 'never', //setup intents do not support redirects
			],
		];

		$intent = null;
		if ( ( $this->apiMode === 'test' && $this->usingWpTestPlatform ) || ( $this->apiMode === 'live' && $this->usingWpLivePlatform ) ) {
			$accountId = $this->apiMode === 'test' ? $this->testStripeAcountId : $this->liveStripeAcountId;
			$intent = $this->remoteRequest(
				'post',
				'/setup_intents?mode=' . $this->apiMode . '&accountId=' . $accountId,
				$params
			);
		} else {
			$intent = json_decode( $this->stripe->setupIntents->create( $params )->toJSON() );
		}

		return $intent;
	}

	/**
	 * @param $stripeSetupIntentId
	 *
	 * @return \StripeWPFS\Stripe\SetupIntent
	 * @throws \StripeWPFS\Stripe\Exception\ApiErrorException
	 */
	function retrieveSetupIntent( $stripeSetupIntentId ) {
		$intent = null;
		if ( ( $this->apiMode === 'test' && $this->usingWpTestPlatform ) || ( $this->apiMode === 'live' && $this->usingWpLivePlatform ) ) {
			$accountId = $this->apiMode === 'test' ? $this->testStripeAcountId : $this->liveStripeAcountId;
			$intent = $this->remoteRequest(
				'get',
				'/setup_intents/' . $stripeSetupIntentId . '?mode=' . $this->apiMode . '&accountId=' . $accountId
			);
		} else {
			$intent = json_decode( $this->stripe->setupIntents->retrieve( $stripeSetupIntentId )->toJSON() );
		}

		return $intent;
	}

	/**
	 * @throws \StripeWPFS\Stripe\Exception\ApiErrorException
	 * @throws WPFS_UserFriendlyException
	 */
	function attachPaymentMethodToStripeCustomer( $stripeCustomer, $currentPaymentMethod ) {
		$attachedPaymentMethod = null;

		if ( isset( $stripeCustomer ) && isset( $currentPaymentMethod ) ) {
			$params = [ 'customer' => $stripeCustomer->id ];
			if ( $this->apiMode === 'test' && $this->usingWpTestPlatform ) {
				$attachedPaymentMethod = $this->remoteRequest(
					'post',
					'/payment_methods/' . $currentPaymentMethod->id . '/attach?mode=test&accountId=' . $this->testStripeAcountId,
					$params
				);
			} elseif ( $this->apiMode === 'live' && $this->usingWpLivePlatform ) {
				$attachedPaymentMethod = $this->remoteRequest(
					'post',
					'/payment_methods/' . $currentPaymentMethod->id . '/attach?mode=live&accountId=' . $this->liveStripeAcountId,
					$params
				);
			} else {
				if ( is_null( $currentPaymentMethod->customer ) ) {
					$this->stripe->paymentMethods->attach(
						$currentPaymentMethod->id,
						$params
					);
					$this->logger->debug( __FUNCTION__, 'PaymentMethod attached.' );
				}
				$attachedPaymentMethod = $currentPaymentMethod;
			}
		}

		return $attachedPaymentMethod;
	}

	/**
	 * Attaches the given PaymentMethod to the given Customer if the Customer do not have an identical PaymentMethod
	 * by card fingerprint.
	 *
	 * @param \StripeWPFS\Stripe\Customer $stripeCustomer
	 * @param \StripeWPFS\Stripe\PaymentMethod $currentPaymentMethod
	 * @param bool $setToDefault
	 * @throws Stripe\Exception\ApiErrorException
	 * @throws WPFS_UserFriendlyException
	 * @return \StripeWPFS\Stripe\PaymentMethod the attached PaymentMethod or the existing one
	 */
	function attachPaymentMethodToCustomerIfMissing( $stripeCustomer, $currentPaymentMethod, $setToDefault = false ) {
		$attachedPaymentMethod = null;

		if ( isset( $stripeCustomer ) && isset( $currentPaymentMethod ) ) {
			// WPFS-983: tnagy find existing PaymentMethod with identical fingerprint and reuse it
			$existingStripePaymentMethod = $this->findExistingPaymentMethodByFingerPrintAndExpiry(
				$stripeCustomer,
				$currentPaymentMethod->card->fingerprint,
				$currentPaymentMethod->card->exp_year,
				$currentPaymentMethod->card->exp_month
			);
			if ( isset( $existingStripePaymentMethod ) ) {
				$this->logger->debug(
					__FUNCTION__,
					'PaymentMethod with identical card fingerprint exists, won\'t attach.'
				);

				$attachedPaymentMethod = $existingStripePaymentMethod;
			} else {
				if ( is_null( $currentPaymentMethod->customer ) ) {
					$this->attachPaymentMethodToStripeCustomer( $stripeCustomer, $currentPaymentMethod );

					$this->logger->debug( __FUNCTION__, 'PaymentMethod ' . $currentPaymentMethod->id . ' attached.' );
				}
				$attachedPaymentMethod = $currentPaymentMethod;
			}
			if ( $setToDefault ) {
				$updateCustomerBody = [
					'invoice_settings' => [
						'default_payment_method' => $attachedPaymentMethod->id
					]
				];
				$this->updateCustomerDetails( $stripeCustomer, $updateCustomerBody );
				$this->logger->debug( __FUNCTION__, 'Default PaymentMethod updated.' );
			}

		}

		return $attachedPaymentMethod;
	}

	/**
	 * Find a Customer's PaymentMethod by fingerprint if exists.
	 *
	 * @param \StripeWPFS\Stripe\Customer $stripeCustomer
	 * @param string $paymentMethodCardFingerPrint
	 * @param $expiryYear
	 * @param $expiryMonth
	 *
	 * @return null|\StripeWPFS\Stripe\PaymentMethod the existing PaymentMethod
	 * @throws Stripe\Exception\ApiErrorException
	 */
	public function findExistingPaymentMethodByFingerPrintAndExpiry(
		$stripeCustomer,
		$paymentMethodCardFingerPrint,
		$expiryYear,
		$expiryMonth
	) {
		if ( empty( $paymentMethodCardFingerPrint ) ) {
			return null;
		}

		$paymentMethodBody = [
			'customer' => $stripeCustomer->id,
			'type' => 'card'
		];

		$query_string = $this->build_query( $paymentMethodBody );
		if ( $this->apiMode === 'test' && $this->usingWpTestPlatform ) {
			$paymentMethods = $this->remoteRequest(
				'get',
				'/payment_methods?mode=test&accountId=' . $this->testStripeAcountId . '&' . $query_string
			);
		} elseif ( $this->apiMode === 'live' && $this->usingWpLivePlatform ) {
			$paymentMethods = $this->remoteRequest(
				'get',
				'/payment_methods?mode=live&accountId=' . $this->liveStripeAcountId . '&' . $query_string
			);
		} else {
			// fallback to using the Stripe API directly
			$paymentMethods = json_decode( $this->stripe->paymentMethods->all( $paymentMethodBody )->toJSON() );
		}
		$existingPaymentMethod = null;
		if ( isset( $paymentMethods ) && isset( $paymentMethods->data ) ) {
			foreach ( $paymentMethods->data as $paymentMethod ) {
				/**
				 * @var \StripeWPFS\Stripe\PaymentMethod $paymentMethod
				 */
				if ( is_null( $existingPaymentMethod ) ) {
					if ( isset( $paymentMethod ) && isset( $paymentMethod->card ) && isset( $paymentMethod->card->fingerprint ) ) {
						if (
							$paymentMethod->card->fingerprint == $paymentMethodCardFingerPrint &&
							$paymentMethod->card->exp_year == $expiryYear &&
							$paymentMethod->card->exp_month == $expiryMonth
						) {
							$existingPaymentMethod = $paymentMethod;
						}
					}
				}
			}
		}

		return $existingPaymentMethod;
	}

	/**
	 * @param $subscriptionId string
	 *
	 * @throws \StripeWPFS\Stripe\Exception\ApiErrorException
	 */
	public function activateCancelledSubscription( $subscriptionId ) {
		$subscription = $this->retrieveSubscription( $subscriptionId );

		do_action( MM_WPFS::ACTION_NAME_BEFORE_SUBSCRIPTION_ACTIVATION, $subscriptionId );
		if ( is_array( $subscription ) ) {
			$subscription['cancel_at_period_end'] = false;
		} else {
			$subscription->cancel_at_period_end = false;
		}
		$this->updateSubscription($subscription);
		// $this->stripe->subscriptions->update( $subscriptionId, $subscription );

		do_action( MM_WPFS::ACTION_NAME_AFTER_SUBSCRIPTION_ACTIVATION, $subscriptionId );
	}

	/**
	 * @param $stripeSubscriptionId
	 */
	private function fireBeforeSubscriptionCancellationAction( $stripeSubscriptionId ) {
		do_action( MM_WPFS::ACTION_NAME_BEFORE_SUBSCRIPTION_CANCELLATION, $stripeSubscriptionId );
	}

	/**
	 * @param $stripeSubscriptionId
	 */
	private function fireAfterSubscriptionCancellationAction( $stripeSubscriptionId ) {
		do_action( MM_WPFS::ACTION_NAME_AFTER_SUBSCRIPTION_CANCELLATION, $stripeSubscriptionId );
	}

	/**
	 * @param $stripeCustomerId
	 * @param $stripeSubscriptionId
	 * @param bool $atPeriodEnd
	 *
	 * @return bool
	 * @throws \StripeWPFS\Stripe\Exception\ApiErrorException
	 * @throws WPFS_UserFriendlyException
	 */
	public function cancelSubscription( $stripeCustomerId, $stripeSubscriptionId, $atPeriodEnd = false ) {
		if ( ! empty( $stripeSubscriptionId ) ) {
			$subscription = $this->retrieveSubscription( $stripeSubscriptionId );

			if ( $subscription ) {
				$this->fireBeforeSubscriptionCancellationAction( $stripeSubscriptionId );

				/** @noinspection PhpUnusedLocalVariableInspection */
				$cancellationResult = null;
				if ( $this->apiMode === 'test' && $this->usingWpTestPlatform ) {
					if ( $atPeriodEnd ) {
						$this->remoteRequest(
							'post',
							'/subscriptions/' . $stripeSubscriptionId . '?mode=test&accountId=' . $this->testStripeAcountId,
							[
								'cancel_at_period_end' => true
							]
						);
					} else {
						$cancellationResult = $this->remoteRequest(
							'delete',
							'/subscriptions/' . $stripeSubscriptionId . '?mode=test&accountId=' . $this->testStripeAcountId
						);
					}
				} elseif ( $this->apiMode === 'live' && $this->usingWpLivePlatform ) {
					if ( $atPeriodEnd ) {
						$this->remoteRequest(
							'post',
							'/subscriptions/' . $stripeSubscriptionId . '?mode=live&accountId=' . $this->liveStripeAcountId,
							[
								'cancel_at_period_end' => true
							]
						);
					} else {
						$cancellationResult = $this->remoteRequest(
							'delete',
							'/subscriptions/' . $stripeSubscriptionId . '?mode=live&accountId=' . $this->liveStripeAcountId
						);
					}
				} else {
					if ( $atPeriodEnd ) {
						$cancellationResult = json_decode( $this->stripe->subscriptions->update(
							$stripeSubscriptionId,
							[
								'cancel_at_period_end' => true
							]
						)->toJSON() );
					} else {
						$cancellationResult = $this->stripe->subscriptions->cancel( $stripeSubscriptionId );
					}
				}

				$this->fireAfterSubscriptionCancellationAction( $stripeSubscriptionId );

				if ( isset( $cancellationResult ) ) {
					return true;
				}
			}
		}

		return false;
	}

	/**
	 * @param $params array
	 *
	 * @return \StripeWPFS\Stripe\Collection
	 * @throws \StripeWPFS\Stripe\Exception\ApiErrorException
	 * @throws WPFS_UserFriendlyException
	 */
	public function listSubscriptionsWithParams( $params ) {
		$subscriptions = null;
		$query_string = $this->build_query( $params );
		if ( $this->apiMode === 'test' && $this->usingWpTestPlatform ) {
			$subscriptions = $this->remoteRequest(
				'get',
				'/subscriptions?mode=test&accountId=' . $this->testStripeAcountId . '&' . $query_string
			);
			$subscriptions = $subscriptions->data;
		} elseif ( $this->apiMode === 'live' && $this->usingWpLivePlatform ) {
			$subscriptions = $this->remoteRequest(
				'get',
				'/subscriptions?mode=live&accountId=' . $this->liveStripeAcountId . '&' . $query_string
			);
			$subscriptions = $subscriptions->data;
		} else {
			$subscriptions = json_decode( $this->stripe->subscriptions->all( $params )->toJSON() );
		}
		return $subscriptions;
	}

	/**
	 * @param $params array
	 *
	 * @return \StripeWPFS\Stripe\Collection
	 * @throws \StripeWPFS\Stripe\Exception\ApiErrorException
	 * @throws WPFS_UserFriendlyException
	 */
	public function listInvoicesWithParams( $params ) {
		$invoices = null;
		$query_string = $this->build_query( $params );
		if ( $this->apiMode === 'test' && $this->usingWpTestPlatform ) {
			$invoices = $this->remoteRequest(
				'get',
				'/invoices?mode=test&accountId=' . $this->testStripeAcountId. '&' . $query_string
			);
			$invoices = $invoices->data;
		} elseif ( $this->apiMode === 'live' && $this->usingWpLivePlatform ) {
			$invoices = $this->remoteRequest(
				'get',
				'/invoices?mode=live&accountId=' . $this->liveStripeAcountId. '&' . $query_string
			);
			$invoices = $invoices->data;
		} else {
			$invoices = json_decode( $this->stripe->invoices->all( $params )->toJSON() );
			$invoices = $invoices->data;
		}
		return $invoices;
	}

	/**
	 * @param $params array
	 *
	 * @return \StripeWPFS\Stripe\Collection
	 * @throws \StripeWPFS\Stripe\Exception\ApiErrorException
	 * @throws WPFS_UserFriendlyException
	 */
	public function listPaymentMethodsWithParams( $params ) {
		$paymentMethods = null;
		$query_string = $this->build_query( $params );

		if ( $this->apiMode === 'test' && $this->usingWpTestPlatform ) {
			$paymentMethods = $this->remoteRequest(
				'get',
				'/payment_methods?mode=test&accountId=' . $this->testStripeAcountId . '&' . $query_string
			);
		} elseif ( $this->apiMode === 'live' && $this->usingWpLivePlatform ) {
			$paymentMethods = $this->remoteRequest(
				'get',
				'/payment_methods?mode=live&accountId=' . $this->liveStripeAcountId . '&' . $query_string
			);
		} else {
			$paymentMethods = json_decode( $this->stripe->paymentMethods->all( $params )->toJSON() );
		}
		return $paymentMethods;
	}

	/**
	 * @param $subscriptionID
	 *
	 * @throws \StripeWPFS\Stripe\Exception\ApiErrorException
	 * @throws WPFS_UserFriendlyException
	 */
	function retrieveSubscription( $subscriptionID ) {
		$subscription = null;
		if ( $this->apiMode === 'test' && $this->usingWpTestPlatform ) {
			$subscription = $this->remoteRequest(
				'get',
				'/subscriptions/' . $subscriptionID . '?mode=test&accountId=' . $this->testStripeAcountId
			);
		} elseif ( $this->apiMode === 'live' && $this->usingWpLivePlatform ) {
			$subscription = $this->remoteRequest(
				'get',
				'/subscriptions/' . $subscriptionID . '?mode=live&accountId=' . $this->liveStripeAcountId
			);
		} else {
			$subscription = json_decode( $this->stripe->subscriptions->retrieve( $subscriptionID )->toJSON() );
		}
		return $subscription;
	}

	/**
	 * @param $subscriptionId string
	 * @param $params array
	 *
	 * @return \StripeWPFS\Stripe\Subscription
	 * @throws \StripeWPFS\Stripe\Exception\ApiErrorException
	 * @throws WPFS_UserFriendlyException
	 */
	public function retrieveSubscriptionWithParams( $subscriptionId, $params ) {
		$stripeSubscription = null;
		$query_string = $this->build_query( $params );
		if ( $this->apiMode === 'test' && $this->usingWpTestPlatform ) {
			$stripeSubscription = $this->remoteRequest(
				'get',
				'/subscriptions/' . $subscriptionId . '?mode=test&accountId=' . $this->testStripeAcountId . '&' . $query_string
			);
		} elseif ( $this->apiMode === 'live' && $this->usingWpLivePlatform ) {
			$stripeSubscription = $this->remoteRequest(
				'get',
				'/subscriptions/' . $subscriptionId . '?mode=live&accountId=' . $this->liveStripeAcountId . '&' . $query_string
			);
		} else {
			$stripeSubscription = json_decode( $this->stripe->subscriptions->retrieve(
				$subscriptionId,
				$params
			)->toJSON() );
		}

		return $stripeSubscription;
	}

	/**
	 * @param $stripeSubscriptionId
	 * @param $newPlanId
	 * @param $newQuantity
	 */
	protected function fireBeforeSubscriptionUpdateAction( $stripeSubscriptionId, $newPlanId, $newQuantity ) {
		$params = [ 
			'stripeSubscriptionId' => $stripeSubscriptionId,
			'planId' => $newPlanId,
			'quantity' => $newQuantity
		];

		do_action( MM_WPFS::ACTION_NAME_BEFORE_SUBSCRIPTION_UPDATE, $params );
	}

	/**
	 * @param $stripeSubscriptionId
	 * @param $newPlanId
	 * @param $newQuantity
	 */
	protected function fireAfterSubscriptionUpdateAction( $stripeSubscriptionId, $newPlanId, $newQuantity ) {
		$params = [ 
			'stripeSubscriptionId' => $stripeSubscriptionId,
			'planId' => $newPlanId,
			'quantity' => $newQuantity
		];

		do_action( MM_WPFS::ACTION_NAME_AFTER_SUBSCRIPTION_UPDATE, $params );
	}

	/**
	 * @param $stripeCustomerId
	 * @param $stripeSubscriptionId
	 * @param $planId
	 * @param $newPlanQuantity
	 * @throws \StripeWPFS\Stripe\Exception\ApiErrorException
	 * @throws WPFS_UserFriendlyException
	 * @return bool
	 */
	public function updateSubscriptionPlanAndQuantity(
		$stripeCustomerId,
		$stripeSubscriptionId,
		$planId,
		$planQuantity = null
	) {
		if ( ! empty( $stripeSubscriptionId ) && ! empty( $planId ) ) {
			/* @var $subscription \StripeWPFS\Stripe\Subscription */
			$subscription = $this->retrieveSubscription( $stripeSubscriptionId );

			if ( ! empty( $planQuantity ) && is_numeric( $planQuantity ) ) {
				$newPlanQuantity = intval( $planQuantity );
			} else {
				$newPlanQuantity = $subscription->quantity;
			}

			if ( isset( $subscription ) ) {
				$parameters = [];
				$performUpdate = false;
				$planUpdated = false;
				// tnagy update subscription plan
				if ( $subscription->plan != $planId ) {
					$parameters = array_merge( $parameters, [ 'plan' => $planId ] );
					$planUpdated = true;
					$performUpdate = true;
				}
				// tnagy update subscription quantity
				$allowMultipleSubscriptions = false;
				if ( isset( $subscription->metadata ) && isset( $subscription->metadata->allow_multiple_subscriptions ) ) {
					$allowMultipleSubscriptions = boolval( $subscription->metadata->allow_multiple_subscriptions );
				}
				$minimumQuantity = MM_WPFS_Utils::getMinimumPlanQuantityOfSubscription( $subscription );
				$maximumQuantity = MM_WPFS_Utils::getMaximumPlanQuantityOfSubscription( $subscription );
				if ( $allowMultipleSubscriptions ) {
					if ( $minimumQuantity > 0 && $newPlanQuantity < $minimumQuantity ) {
						throw new Exception(
							sprintf(
								/* translators: Error message displayed when subscriber tries to set a quantity for a subscription which is beyond allowed value */
								__(
									"Subscription quantity '%d' is not allowed for this subscription!",
									'wp-full-stripe-free'
								),
								$newPlanQuantity
							)
						);
					}
					if ( $maximumQuantity > 0 && $newPlanQuantity > $maximumQuantity ) {
						throw new Exception(
							sprintf(
								/* translators: Error message displayed when subscriber tries to set a quantity for a subscription which is over allowed value */
								__(
									"Subscription quantity '%d' is not allowed for this subscription!",
									'wp-full-stripe-free'
								),
								$newPlanQuantity
							)
						);
					}
					if ( $subscription->quantity != intval( $newPlanQuantity ) || $planUpdated ) {
						$parameters = array_merge( $parameters, [ 'quantity' => $newPlanQuantity ] );
						$performUpdate = true;
					}
				} elseif ( $newPlanQuantity > 1 ) {
					throw new Exception(
						/* translators: Error message displayed when subscriber tries to set a quantity for a
						 * subscription where quantity other than one is not allowed.
						 */
						__( 'Quantity update is not allowed for this subscription!', 'wp-full-stripe-free' )
					);
				}
			} else {
				throw new Exception(
					sprintf(
						/* translators: Error message displayed when a subscription is not found.
						 * p1: Subscription identifier
						 */
						__( "Subscription '%s' not found!", 'wp-full-stripe-free' ),
						$stripeSubscriptionId
					)
				);
			}
			if ( $performUpdate ) {
				$this->fireBeforeSubscriptionUpdateAction( $stripeSubscriptionId, $planId, $newPlanQuantity );
				if ( $this->apiMode === 'test' && $this->usingWpTestPlatform ) {
					$this->remoteRequest(
						'post',
						'/subscriptions/' . $stripeSubscriptionId . '?mode=test&accountId=' . $this->testStripeAcountId,
						$parameters
					);
				} elseif ( $this->apiMode === 'live' && $this->usingWpLivePlatform ) {
					$this->remoteRequest(
						'post',
						'/subscriptions/' . $stripeSubscriptionId . '?mode=live&accountId=' . $this->liveStripeAcountId,
						$parameters
					);
				} else {
					$this->stripe->subscriptions->update( $stripeSubscriptionId, $parameters );
				}
				$this->fireAfterSubscriptionUpdateAction( $stripeSubscriptionId, $planId, $newPlanQuantity );
			}

			return true;
		} else {
			// This is an internal error, no need to localize it
			throw new Exception( 'Invalid parameters!' );
		}
	}

	/**
	 * @param $chargeId
	 *
	 * @throws \StripeWPFS\Stripe\Exception\ApiErrorException
	 * @throws WPFS_UserFriendlyException
	 */
	function captureCharge( $chargeId ) {
		$charge = null;

		if ( $this->apiMode === 'test' && $this->usingWpTestPlatform ) {
			$charge = $this->remoteRequest(
				'get',
				'/charges/' . $chargeId . '?mode=test&accountId=' . $this->testStripeAcountId
			);

			if ( isset( $charge ) ) {
				return $this->remoteRequest(
					'post',
					'/charges/' . $chargeId . '/capture?mode=test&accountId=' . $this->testStripeAcountId
				);
			}

		} elseif ( $this->apiMode === 'live' && $this->usingWpLivePlatform ) {
			$charge = $this->remoteRequest(
				'get',
				'/charges/' . $chargeId . '?mode=live&accountId=' . $this->liveStripeAcountId
			);

			if ( isset( $charge ) ) {
				return $this->remoteRequest(
					'post',
					'/charges/' . $chargeId . '/capture?mode=live&accountId=' . $this->liveStripeAcountId
				);
			}
		} else {
			$charge = json_decode( $this->stripe->charges->retrieve( $chargeId )->toJSON() );
		}

		return $charge;
	}

	public function getLatestCharge( $paymentIntent ) {
		if ( is_string( $paymentIntent->latest_charge ) ) {
			$charge = null;
			if ( $this->apiMode === 'test' && $this->usingWpTestPlatform ) {
				$charge = $this->remoteRequest(
					'get',
					'/charges/' . $paymentIntent->latest_charge . '?mode=test&accountId=' . $this->testStripeAcountId
				);
			} elseif ( $this->apiMode === 'live' && $this->usingWpLivePlatform ) {
				$charge = $this->remoteRequest(
					'get',
					'/charges/' . $paymentIntent->latest_charge . '?mode=live&accountId=' . $this->liveStripeAcountId
				);
			} else {
				$charge = json_decode( $this->stripe->charges->retrieve( $paymentIntent->latest_charge )->toJSON() );
			}
			return $charge;
		} else {
			return $paymentIntent->latest_charge;
		}
	}

	/**
	 * @param $paymentIntentId
	 *
	 * @throws \StripeWPFS\Stripe\Exception\ApiErrorException
	 * @throws WPFS_UserFriendlyException
	 */
	public function capturePaymentIntent( $paymentIntentId ) {
		$paymentIntent = $this->retrievePaymentIntent( $paymentIntentId );
		if ( isset( $paymentIntent ) ) {
			if ( $this->apiMode === 'test' && $this->usingWpTestPlatform ) {
				return $this->remoteRequest(
					'post',
					'/payment_intents/' . $paymentIntent->id . '/capture?mode=test&accountId=' . $this->testStripeAcountId
				);
			} elseif ( $this->apiMode === 'live' && $this->usingWpLivePlatform ) {
				return $this->remoteRequest(
					'post',
					'/payment_intents/' . $paymentIntent->id . '/capture?mode=live&accountId=' . $this->liveStripeAcountId
				);
			} else {
				return json_decode( $this->stripe->paymentIntents->capture( $paymentIntentId )->toJSON() );
			}
		}

		return $paymentIntent;
	}

	/**
	 * @param $chargeId
	 *
	 * @throws \StripeWPFS\Stripe\Exception\ApiErrorException
	 * @throws WPFS_UserFriendlyException
	 */
	function refundCharge( $chargeId ) {
		$refund = null;
		$refundBody = [
			'charge' => $chargeId->id ? $chargeId->id : $chargeId
		];

		if ( $this->apiMode === 'test' && $this->usingWpTestPlatform ) {
			$refund = $this->remoteRequest(
				'post',
				'/refunds?mode=test&accountId=' . $this->testStripeAcountId,
				$refundBody
			);
		} elseif ( $this->apiMode === 'live' && $this->usingWpLivePlatform ) {
			$refund = $this->remoteRequest(
				'post',
				'/refunds?mode=live&accountId=' . $this->liveStripeAcountId,
				$refundBody
			);
		} else {
			$refund = json_decode( $this->stripe->refunds->create( [ 'charge' => $chargeId ] )->toJSON() );
		}
		return $refund;
	}

	/**
	 * @param $paymentIntentId
	 *
	 * @return \StripeWPFS\Stripe\PaymentIntent|\StripeWPFS\Stripe\Refund
	 * @throws \StripeWPFS\Stripe\Exception\ApiErrorException
	 * @throws WPFS_UserFriendlyException
	 */
	public function cancelOrRefundPaymentIntent( $paymentIntentId ) {
		$paymentIntent = $this->retrievePaymentIntent( $paymentIntentId );
		if ( isset( $paymentIntent ) ) {
			/* @var $paymentIntent \StripeWPFS\Stripe\PaymentIntent */
			if (
				\StripeWPFS\Stripe\PaymentIntent::STATUS_REQUIRES_PAYMENT_METHOD === $paymentIntent->status
				|| \StripeWPFS\Stripe\PaymentIntent::STATUS_REQUIRES_CAPTURE === $paymentIntent->status
				|| \StripeWPFS\Stripe\PaymentIntent::STATUS_REQUIRES_CONFIRMATION === $paymentIntent->status
				|| \StripeWPFS\Stripe\PaymentIntent::STATUS_REQUIRES_ACTION === $paymentIntent->status
			) {
				if ( $this->apiMode === 'test' && $this->usingWpTestPlatform ) {
					return $this->remoteRequest(
						'post',
						'/payment_intents/' . $paymentIntent->id . '/cancel?mode=test&accountId=' . $this->testStripeAcountId
					);
				} elseif ( $this->apiMode === 'live' && $this->usingWpLivePlatform ) {
					return $this->remoteRequest(
						'post',
						'/payment_intents/' . $paymentIntent->id . '/cancel?mode=live&accountId=' . $this->liveStripeAcountId
					);
				} else {
					return json_decode( $this->stripe->paymentIntents->cancel( $paymentIntentId )->toJSON() );
				}
			} elseif (
				\StripeWPFS\Stripe\PaymentIntent::STATUS_PROCESSING === $paymentIntent->status
				|| \StripeWPFS\Stripe\PaymentIntent::STATUS_SUCCEEDED === $paymentIntent->status
			) {
				return $this->refundCharge( $paymentIntent->latest_charge );
			}
		}
		return $paymentIntent;
	}

	/**
	 * Update payment intent with metadata and description
	 *
	 * @param $paymentIntent
	 * @param bool $includeAmount
	 * @param $stripeReceiptEmailAddress
	 * @throws \StripeWPFS\Stripe\Exception\ApiErrorException
	 * @throws WPFS_UserFriendlyException
	 */
	public function updatePaymentIntent( $paymentIntent, $includeAmount = false, $stripeReceiptEmailAddress = null ) {
		$updateIntentBody = [
			"metadata" => $paymentIntent->metadata,
			"description" => $paymentIntent->description,
			"validLicense" => $this->validLicense,
		];

		if ( isset( $stripeReceiptEmailAddress ) ) {
			$updateIntentBody['receipt_email'] = $stripeReceiptEmailAddress;
		}

		if ( $includeAmount && isset( $paymentIntent->amount ) ) {
			$updateIntentBody["amount"] = $paymentIntent->amount;
		}

		if ( $this->apiMode === 'test' && $this->usingWpTestPlatform ) {
			$this->remoteRequest(
				'post',
				'/payment_intents/' . $paymentIntent->id . '?mode=test&accountId=' . $this->testStripeAcountId . '&apiVersion=' . $this->userVersion,
				$updateIntentBody
			);
		} elseif ( $this->apiMode === 'live' && $this->usingWpLivePlatform ) {
			$this->remoteRequest(
				'post',
				'/payment_intents/' . $paymentIntent->id . '?mode=live&accountId=' . $this->liveStripeAcountId . '&apiVersion=' . $this->userVersion,
				$updateIntentBody
			);
		} else {
			// update the payment intent using the stripe API
			$params = [
				'metadata' => $paymentIntent->metadata,
				'description' => $paymentIntent->description,
			];
			$this->stripe->paymentIntents->update( $paymentIntent->id, $params );
		}
	}

	/**
	 * @throws \StripeWPFS\Stripe\Exception\ApiErrorException
	 * @throws WPFS_UserFriendlyException
	 */
	public function updateCustomer( $stripeCustomer ) {
		$address = null;
		$shipping = null;
		// trying to deal with the different types of objects the customer and/or the address can have
		if ( is_object( $stripeCustomer->address ) && get_class( $stripeCustomer->address ) === 'stdClass' ) {
			$address = json_decode( json_encode( $stripeCustomer->address ), true );
		} elseif ( is_array( $stripeCustomer->address ) ) {
			$address = $stripeCustomer->address;
		} elseif ( isset( $stripeCustomer->address ) ) {
			$address = $stripeCustomer->address->toArray();
		}
		if ( is_object( $stripeCustomer->shipping ) && get_class( $stripeCustomer->shipping ) === 'stdClass' ) {
			$shipping = json_decode( json_encode( $stripeCustomer->shipping ), true );
		} elseif ( is_array( $stripeCustomer->shipping ) ) {
			$shipping = $stripeCustomer->shipping;
		} elseif ( isset( $stripeCustomer->shipping ) ) {
			$shipping = $stripeCustomer->shipping->toArray();
		}

		// update the customer using the stripe API
		$params = [
			'address' => $address,
			'name' => $stripeCustomer->name,
			'shipping' => $shipping,
			'description' => $stripeCustomer->description,
		];
		$this->updateCustomerDetails( $stripeCustomer, $params );
	}

	/**
	 * Update subscription metadata
	 *
	 * @param $stripeSubscription
	 * @throws WPFS_UserFriendlyException
	 */
	public function updateSubscription( $stripeSubscription ) {
		// we're just updating the metadata at this point
		// also handle cancellation updates

		$subscriptionMetadata = [
			'metadata' => $stripeSubscription->metadata,
			'cancel_at_period_end' => $stripeSubscription->cancel_at_period_end
		];

		if ( $this->apiMode === 'test' && $this->usingWpTestPlatform ) {
			$this->remoteRequest(
				'post',
				'/subscriptions/' . $stripeSubscription->id . '?mode=test&accountId=' . $this->testStripeAcountId,
				$subscriptionMetadata
			);
		} elseif ( $this->apiMode === 'live' && $this->usingWpLivePlatform ) {
			$this->remoteRequest(
				'post',
				'/subscriptions/' . $stripeSubscription->id . '?mode=live&accountId=' . $this->liveStripeAcountId,
				$subscriptionMetadata
			);
		} else {
			// update the subscription using the stripe API
			$this->stripe->subscriptions->update( $stripeSubscription->id, $subscriptionMetadata );
		}
	}

	/**
	 * @throws \StripeWPFS\Stripe\Exception\ApiErrorException
	 * @throws WPFS_UserFriendlyException
	 */
	public function confirmSetupIntent( $setupIntent ) {
		$params = [
			'payment_method' => $setupIntent->payment_method
		];

		if ( ( $this->apiMode === 'test' && $this->usingWpTestPlatform ) || ( $this->apiMode === 'live' && $this->usingWpLivePlatform ) ) {
			$accountId = $this->apiMode === 'test' ? $this->testStripeAcountId : $this->liveStripeAcountId;
			$setupIntent = $this->remoteRequest(
				'post',
				'/setup_intents/' . $setupIntent->id . '/confirm?mode=' . $this->apiMode . '&accountId=' . $accountId,
				$params
			);
		} else {
			// confirm the setup intent using the stripe API
			$setupIntent = json_decode( $this->stripe->setupIntents->confirm(
				$setupIntent->id,
				$params
			)->toJSON() );
		}

		return $setupIntent;
	}


	/**
	 * Confirms a payment intent with the provided payment intent ID and payment method ID.
	 *
	 * @param string $paymentIntentId The ID of the payment intent.
	 * @param string $paymentMethodId The ID of the payment method.
	 */
	public function confirmPaymentIntent( $paymentIntentId, $paymentMethodId ) {
		$paymentIntent = null;
		$params = [
			'payment_method' => $paymentMethodId
		];

		if ( ( $this->apiMode === 'test' && $this->usingWpTestPlatform ) || ( $this->apiMode === 'live' && $this->usingWpLivePlatform ) ) {
			$accountId = $this->apiMode === 'test' ? $this->testStripeAcountId : $this->liveStripeAcountId;
			$paymentIntent = $this->remoteRequest(
				'post',
				'/payment_intents/' . $paymentIntentId . '/confirm?mode=' . $this->apiMode . '&accountId=' . $accountId,
				$params
			);
		} else {
			// confirm the setup intent using the stripe API
			$paymentIntent = json_decode( $this->stripe->paymentIntents->confirm(
				$paymentIntentId,
				$params
			)->toJSON() );
		}
		return $paymentIntent;
	}

	private function updateCustomerDetails( $stripeCustomer, $params ) {
		if ( $this->apiMode === 'test' && $this->usingWpTestPlatform ) {
			$this->remoteRequest(
				'post',
				'/customers/' . $stripeCustomer->id . '?mode=test&accountId=' . $this->testStripeAcountId,
				$params
			);
		} elseif ( $this->apiMode === 'live' && $this->usingWpLivePlatform ) {
			$this->remoteRequest(
				'post',
				'/customers/' . $stripeCustomer->id . '?mode=live&accountId=' . $this->liveStripeAcountId,
				$params
			);
		} else {
			$this->stripe->customers->update( $stripeCustomer->id, $params );
		}
	}

	/**
	 * @param $parameters array
	 *
	 * @throws \StripeWPFS\Stripe\Exception\ApiErrorException
	 * @throws WPFS_UserFriendlyException
	 */
	public function createCheckoutSession( $parameters ) {
		$session = null;
		$parameters = apply_filters( 'fullstripe_checkout_session_parameters', $parameters );
		if ( $this->apiMode === 'test' && $this->usingWpTestPlatform ) {
			$parameters = array_merge( $parameters, [ 'validLicense' => $this->validLicense ] );
			$session = $this->remoteRequest(
				'post',
				'/checkout?mode=test&accountId=' . $this->testStripeAcountId . '&apiVersion=' . $this->userVersion,
				apply_filters( 'fullstripe_checkout_session_parameters', $parameters )
			);
		} elseif ( $this->apiMode === 'live' && $this->usingWpLivePlatform ) {
			$parameters = array_merge( $parameters, [ 'validLicense' => $this->validLicense ] );
			$session = $this->remoteRequest(
				'post',
				'/checkout?mode=live&accountId=' . $this->liveStripeAcountId . '&apiVersion=' . $this->userVersion,
				apply_filters( 'fullstripe_checkout_session_parameters', $parameters )
			);
		} else {
			$session = json_decode( $this->stripe->checkout->sessions->create(
				apply_filters(
					'fullstripe_checkout_session_parameters',
					$parameters
				)
			)->toJSON() );
		}
		return $session;
	}

	/**
	 * @param string $stripeInvoiceId
	 * @throws WPFS_UserFriendlyException
	 */
	public function payInvoiceOutOfBand( $stripeInvoiceId ) {
		$invoice = null;
		if ( $this->apiMode === 'test' && $this->usingWpTestPlatform ) {
			$invoice = $this->remoteRequest(
				'post',
				'/invoices/' . $stripeInvoiceId . '/pay?mode=test&accountId=' . $this->testStripeAcountId,
				[ 'paid_out_of_band' => true ]
			);
		} elseif ( $this->apiMode === 'live' && $this->usingWpLivePlatform ) {
			$invoice = $this->remoteRequest(
				'post',
				'/invoices/' . $stripeInvoiceId . '/pay?mode=live&accountId=' . $this->liveStripeAcountId,
				[ 'paid_out_of_band' => true ]
			);
		} else {
			$invoice = json_decode( $this->stripe->invoices->pay(
				$stripeInvoiceId,
				[ 'paid_out_of_band' => true ]
			)->toJSON() );
		}

		return $invoice;
	}

	/**
	 * @param $stripeInvoice
	 * @throws \StripeWPFS\Stripe\Exception\ApiErrorException
	 * @throws WPFS_UserFriendlyException
	 */
	public function finalizeInvoice( $stripeInvoiceId ) {
		$invoice = null;
		$params = [
			'expand' => [ 'payments' ]
		];

		if ( $this->apiMode === 'test' && $this->usingWpTestPlatform ) {
			$invoice = $this->remoteRequest(
				'post',
				'/invoices/' . $stripeInvoiceId . '/finalize?mode=test&accountId=' . $this->testStripeAcountId,
				$params
			);
		} elseif ( $this->apiMode === 'live' && $this->usingWpLivePlatform ) {
			$invoice = $this->remoteRequest(
				'post',
				'/invoices/' . $stripeInvoiceId . '/finalize?mode=live&accountId=' . $this->liveStripeAcountId,
				$params
			);
		} else {
			if ( is_null( $this->stripe ) ) {
				throw new WPFS_UserFriendlyException( 'Stripe client is not initialized' );
			}
			$invoice = json_decode( $this->stripe->invoices->finalizeInvoice( $stripeInvoiceId, $params )->toJSON() );
		}
		return $invoice;
	}

	/**
	 * @throws \StripeWPFS\Stripe\Exception\ApiErrorException
	 * @throws WPFS_UserFriendlyException
	 */
	public function getUpcomingInvoice( $invoiceParams ) {
		$invoice = null;
		if ( $this->apiMode === 'test' && $this->usingWpTestPlatform ) {
			$invoice = $this->remoteRequest(
				'post',
				'/invoices/preview?mode=test&accountId=' . $this->testStripeAcountId,
				$invoiceParams
			);
		} elseif ( $this->apiMode === 'live' && $this->usingWpLivePlatform ) {
			$invoice = $this->remoteRequest(
				'post',
				'/invoices/preview?mode=live&accountId=' . $this->liveStripeAcountId,
				$invoiceParams
			);
		} else {
			if ( is_null( $this->stripe ) ) {
				throw new WPFS_UserFriendlyException( 'Stripe client is not initialized' );
			}
			$invoice = json_decode( $this->stripe->invoices->createPreview( $invoiceParams )->toJSON() );
		}
		return $invoice;
	}

	/**
	 * @return \StripeWPFS\Stripe\StripeClient
	 */
	public function getStripeClient() {
		return $this->stripe;
	}

	/**
	 * @param $priceIds array
	 *
	 * @return array
	 * @throws WPFS_UserFriendlyException
	 */
	public function retrieveProductsByPriceIds( $priceIds ) {
		$products = [];
		$params = [ 'expand' => [ 'product' ] ];
		$query_string = $this->build_query( $params );

		foreach ( $priceIds as $priceId ) {
			$price = null;

			if ( $this->apiMode === 'test' && $this->usingWpTestPlatform ) {
				$price = $this->remoteRequest(
					'get',
					'/prices/' . $priceId . '?mode=test&accountId=' . $this->testStripeAcountId . '&' . $query_string
				);
			} elseif ( $this->apiMode === 'live' && $this->usingWpLivePlatform ) {
				$price = $this->remoteRequest(
					'get',
					'/prices/' . $priceId . '?mode=live&accountId=' . $this->liveStripeAcountId . '&' . $query_string
				);
			} else {
				$price = json_decode( $this->stripe->prices->retrieve( $priceId, $params )->toJSON() );
			}
			array_push( $products, $price->product );
		}

		return $products;
	}

	/**
	 * @param $priceIds array
	 *
	 * @return array
	 */
	public function retrieveProductIdsByPriceIds( $priceIds ) {
		$products = $this->retrieveProductsByPriceIds( $priceIds );
		$productIds = array_map(
			function ($o) {
				return $o->id;
			},
			$products
		);

		return $productIds;
	}


	/**
	 * @param $priceId
	 *
	 * @return \StripeWPFS\Stripe\Price
	 * @throws WPFS_UserFriendlyException
	 */
	public function retrievePriceWithProductExpanded( $priceId ) {
		$price = null;
		$params = [ 'expand' => [ 'product' ] ];
		$query_string = $this->build_query( $params );

		if ( $this->apiMode === 'test' && $this->usingWpTestPlatform ) {
			$price = $this->remoteRequest(
				'get',
				'/prices/' . $priceId . '?mode=test&accountId=' . $this->testStripeAcountId . '&' . $query_string
			);
		} elseif ( $this->apiMode === 'live' && $this->usingWpLivePlatform ) {
			$price = $this->remoteRequest(
				'get',
				'/prices/' . $priceId . '?mode=live&accountId=' . $this->liveStripeAcountId . '&' . $query_string
			);
		} else {
			$price = json_decode( $this->stripe->prices->retrieve(
				$priceId,
				$params
			)->toJSON() );
		}
		return $price;
	}

	/**
	 * @param $stripeCustomerId
	 * @param $taxIdType
	 * @param $taxId
	 * @return \StripeWPFS\Stripe\TaxId
	 * @throws \StripeWPFS\Stripe\Exception\ApiErrorException
	 * @throws WPFS_UserFriendlyException
	 */
	public function createTaxIdForCustomer( $stripeCustomerId, $taxIdType, $taxId ) {
		$taxId = null;
		$taxBody = [
			'type' => $taxIdType,
			'value' => $taxId
		];

		if ( $this->apiMode === 'test' && $this->usingWpTestPlatform ) {
			$taxId = $this->remoteRequest(
				'post',
				'/customers/' . $stripeCustomerId . '/tax_ids?mode=test&accountId=' . $this->testStripeAcountId,
				$taxBody
			);
		} elseif ( $this->apiMode === 'live' && $this->usingWpLivePlatform ) {
			$taxId = $this->remoteRequest(
				'post',
				'/customers/' . $stripeCustomerId . '/tax_ids?mode=live&accountId=' . $this->liveStripeAcountId,
				$taxBody
			);
		} else {
			$taxId = json_decode( $this->stripe->customers->createTaxId( $stripeCustomerId, $taxBody )->toJSON() );
		}
		return $taxId;
	}

	/**
	 * @param $stripeCustomerId
	 * @return \StripeWPFS\Stripe\Collection
	 * @throws \StripeWPFS\Stripe\Exception\ApiErrorException
	 * @throws WPFS_UserFriendlyException
	 */
	public function getTaxIdsForCustomer( $stripeCustomerId ) {
		$taxIds = null;
		if ( $this->apiMode === 'test' && $this->usingWpTestPlatform ) {
			$taxIds = $this->remoteRequest(
				'get',
				'/customers/' . $stripeCustomerId . '/tax_ids?mode=test&accountId=' . $this->testStripeAcountId
			);
			$taxIds = $taxIds->data;
		} elseif ( $this->apiMode === 'live' && $this->usingWpLivePlatform ) {
			$taxIds = $this->remoteRequest(
				'get',
				'/customers/' . $stripeCustomerId . '/tax_ids?mode=live&accountId=' . $this->liveStripeAcountId
			);
			$taxIds = $taxIds->data;
		} else {
			$taxIds = json_decode( $this->stripe->customers->allTaxIds( $stripeCustomerId, [] )->toJSON() );
		}
		return $taxIds;
	}

	/**
	 * Retrieves the customer's name from Stripe and caches it using a transient.
	 *
	 * @param string $stripeCustomerId The ID of the Stripe customer.
	 * @return string The customer's full name or the customer ID if an error occurs.
	 */
	public function getCustomerName( $stripeCustomerId ) {
		$transient_key = 'wpfs_stripe_customer_name_' . $stripeCustomerId;
		$customer_name = get_transient( $transient_key );

		if ( false === $customer_name ) {
			try {
				$customer = $this->retrieveCustomer( $stripeCustomerId );
				if ( isset( $customer->name ) && ! empty( $customer->name ) ) {
					$customer_name = $customer->name;
					set_transient( $transient_key, $customer_name, WEEK_IN_SECONDS );
				} else {
					$customer_name = $stripeCustomerId;
				}
			} catch ( Exception $e ) {
				$customer_name = $stripeCustomerId;
			}
		}

		return $customer_name;
	}
}
