<?php

namespace StripeWPFS\Stripe\Exception\OAuth;

/**
 * Implements properties and methods common to all (non-SPL) Stripe OAuth
 * exceptions.
 */
abstract class OAuthErrorException extends \StripeWPFS\Stripe\Exception\ApiErrorException
{
    protected function constructErrorObject()
    {
        if (null === $this->jsonBody) {
            return null;
        }

        return \StripeWPFS\Stripe\OAuthErrorObject::constructFrom($this->jsonBody);
    }
}
