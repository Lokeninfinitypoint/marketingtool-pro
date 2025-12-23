<?php

namespace StripeWPFS\Stripe\Util;

class EventTypes
{
    const thinEventMapping = [
        // The beginning of the section generated from our OpenAPI spec
        \StripeWPFS\Stripe\Events\V1BillingMeterErrorReportTriggeredEvent::LOOKUP_TYPE => \StripeWPFS\Stripe\Events\V1BillingMeterErrorReportTriggeredEvent::class,
        \StripeWPFS\Stripe\Events\V1BillingMeterNoMeterFoundEvent::LOOKUP_TYPE => \StripeWPFS\Stripe\Events\V1BillingMeterNoMeterFoundEvent::class,
        \StripeWPFS\Stripe\Events\V2CoreEventDestinationPingEvent::LOOKUP_TYPE => \StripeWPFS\Stripe\Events\V2CoreEventDestinationPingEvent::class,
        // The end of the section generated from our OpenAPI spec
    ];
}
