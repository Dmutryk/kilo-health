<?php

namespace App\Services\SubscriptionRequestCreators;

use App\Models\SubscriptionRequest;
use App\Services\Interfaces\SubscriptionRequestCreatorInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

final class ApplePaySubscriptionRequestCreator implements SubscriptionRequestCreatorInterface
{
    const PROVIDER_CODE = 'apple-pay';

    /** Response body array variables path */
    const TRANSACTION_ID = 'responseBody.Latest_receipt_info.original_transaction_id';
    const PURCHASE_DATE = 'responseBody.original_purchase_date';
    const SUBSCRIPTION_USER_ID = 'responseBody.auto_renew_adam_id';
    const SUBSCRIPTION_PRODUCT_ID = 'responseBody.auto_renew_product_id';

    /**
     * @param Request $request
     * @return SubscriptionRequest
     */
    public function create(Request $request): SubscriptionRequest
    {
        $requestArray = $request->json()->all();
        $subscriptionRequest = new SubscriptionRequest();
        $subscriptionRequest->setProviderCode(self::PROVIDER_CODE);
        $subscriptionRequest->setEvent($request->get('notification_type'));
        $subscriptionRequest->setOriginTransactionId( Arr::get($requestArray, self::TRANSACTION_ID));
        $subscriptionRequest->setPurchaseDate(Arr::get($requestArray, self::PURCHASE_DATE));
        $subscriptionRequest->setUserSubscriptionId(Arr::get($requestArray, self::SUBSCRIPTION_USER_ID));
        $subscriptionRequest->setUserSubscriptionProductId(Arr::get($requestArray, self::SUBSCRIPTION_PRODUCT_ID));

        return $subscriptionRequest;
    }
}
