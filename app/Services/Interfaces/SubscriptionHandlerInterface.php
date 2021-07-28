<?php

namespace App\Services\Interfaces;

use App\Models\SubscriptionRequest;

interface SubscriptionHandlerInterface
{
    public function initialSubscription(SubscriptionRequest $subscriptionRequest);

    public function renewedSubscription(SubscriptionRequest $subscriptionRequest);

    public function unsuccessfulRenewal(SubscriptionRequest $subscriptionRequest);

    public function cancelSubscription(SubscriptionRequest $subscriptionRequest);
}
