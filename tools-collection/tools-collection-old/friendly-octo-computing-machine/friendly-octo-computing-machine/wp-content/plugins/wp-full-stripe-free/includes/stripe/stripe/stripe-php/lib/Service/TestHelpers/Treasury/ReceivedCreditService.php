<?php

// File generated from our OpenAPI spec

namespace StripeWPFS\Stripe\Service\TestHelpers\Treasury;

/**
 * @phpstan-import-type RequestOptionsArray from \Stripe\Util\RequestOptions
 *
 * @psalm-import-type RequestOptionsArray from \Stripe\Util\RequestOptions
 */
class ReceivedCreditService extends \StripeWPFS\Stripe\Service\AbstractService
{
    /**
     * Use this endpoint to simulate a test mode ReceivedCredit initiated by a third
     * party. In live mode, you can’t directly create ReceivedCredits initiated by
     * third parties.
     *
     * @param null|array{amount: int, currency: string, description?: string, expand?: string[], financial_account: string, initiating_payment_method_details?: array{type: string, us_bank_account?: array{account_holder_name?: string, account_number?: string, routing_number?: string}}, network: string} $params
     * @param null|RequestOptionsArray|\StripeWPFS\Stripe\Util\RequestOptions $opts
     *
     * @return \StripeWPFS\Stripe\Treasury\ReceivedCredit
     *
     * @throws \StripeWPFS\Stripe\Exception\ApiErrorException if the request fails
     */
    public function create($params = null, $opts = null)
    {
        return $this->request('post', '/v1/test_helpers/treasury/received_credits', $params, $opts);
    }
}
