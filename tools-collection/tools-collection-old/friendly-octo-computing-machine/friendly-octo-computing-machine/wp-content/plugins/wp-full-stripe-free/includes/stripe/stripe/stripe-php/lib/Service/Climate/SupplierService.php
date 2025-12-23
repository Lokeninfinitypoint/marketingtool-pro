<?php

// File generated from our OpenAPI spec

namespace StripeWPFS\Stripe\Service\Climate;

/**
 * @phpstan-import-type RequestOptionsArray from \Stripe\Util\RequestOptions
 *
 * @psalm-import-type RequestOptionsArray from \Stripe\Util\RequestOptions
 */
class SupplierService extends \StripeWPFS\Stripe\Service\AbstractService
{
    /**
     * Lists all available Climate supplier objects.
     *
     * @param null|array{ending_before?: string, expand?: string[], limit?: int, starting_after?: string} $params
     * @param null|RequestOptionsArray|\StripeWPFS\Stripe\Util\RequestOptions $opts
     *
     * @return \StripeWPFS\Stripe\Collection<\StripeWPFS\Stripe\Climate\Supplier>
     *
     * @throws \StripeWPFS\Stripe\Exception\ApiErrorException if the request fails
     */
    public function all($params = null, $opts = null)
    {
        return $this->requestCollection('get', '/v1/climate/suppliers', $params, $opts);
    }

    /**
     * Retrieves a Climate supplier object.
     *
     * @param string $id
     * @param null|array{expand?: string[]} $params
     * @param null|RequestOptionsArray|\StripeWPFS\Stripe\Util\RequestOptions $opts
     *
     * @return \StripeWPFS\Stripe\Climate\Supplier
     *
     * @throws \StripeWPFS\Stripe\Exception\ApiErrorException if the request fails
     */
    public function retrieve($id, $params = null, $opts = null)
    {
        return $this->request('get', $this->buildPath('/v1/climate/suppliers/%s', $id), $params, $opts);
    }
}
