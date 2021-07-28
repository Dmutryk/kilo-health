<?php


namespace App\Services;


use App\Enums\NotificationTypesEnum;
use App\Enums\SubscriptionsEnum;
use App\Enums\SubscriptionStatusesEnum;
use App\Exceptions\SubscriptionMethodNotFound;
use App\Models\Subscription;
use App\Models\SubscriptionProduct;
use App\Models\SubscriptionRequest;
use App\Models\Transaction;
use App\Models\User;
use App\Services\Interfaces\SubscriptionHandlerInterface;
use Carbon\Carbon;

class SubscriptionHandler implements SubscriptionHandlerInterface
{

    public function handle(SubscriptionRequest $subscriptionRequest): void
    {
        switch ($subscriptionRequest->getEvent()) {
            case NotificationTypesEnum::INITIAL_BUY:
                $this->initialSubscription($subscriptionRequest);
                break;
            case NotificationTypesEnum::DID_RENEW:
                $this->renewedSubscription($subscriptionRequest);
                break;
            case NotificationTypesEnum::DID_FAIL_TO_RENEW:
                $this->unsuccessfulRenewal($subscriptionRequest);
                break;
            case NotificationTypesEnum::CANCEL:
                $this->cancelSubscription($subscriptionRequest);
                break;
            default:
                throw new SubscriptionMethodNotFound('SUBSCRIPTION EVENT NOT FOUND');
        }
    }

    public function initialSubscription(SubscriptionRequest $subscriptionRequest): void
    {
        $user = User::firstOrFail('user_subscription_id', $subscriptionRequest->getUserSubscriptionId());

        $transaction = Transaction::create([
            'original_transaction_id' => $subscriptionRequest->getOriginTransactionId(),
            'receipt' => $subscriptionRequest->getReceipt(),
            'purchase_date' => $subscriptionRequest->getPurchaseDate(),
            'event' => $subscriptionRequest->getEvent(),
            'provider_code' => $subscriptionRequest->getProviderCode(),
            'user_subscription_id' => $subscriptionRequest->getUserSubscriptionId(),
            'user_product_id' => $subscriptionRequest->getUserSubscriptionProductId(),
        ]);
        $expiredDate = $this->calculateExpiredDate($subscriptionRequest);

        Subscription::create([
            'user_id' => $user->getId(),
            'transaction_id' => $transaction->getId(),
            'status' => SubscriptionStatusesEnum::ACTIVE,
            'type' => SubscriptionsEnum::BASIC, //just use basic for now
            'expired_date' => $expiredDate
        ]);
    }

    public function renewedSubscription(SubscriptionRequest $subscriptionRequest): void
    {
        $user = User::firstOrFail('user_subscription_id', $subscriptionRequest->getUserSubscriptionId());

        $subscription = $user->getSubscription();//stub for now: search user subscription, if not found throw Exception
        $transaction = Transaction::create([
            'original_transaction_id' => $subscriptionRequest->getOriginTransactionId(),
            'receipt' => $subscriptionRequest->getReceipt(),
            'purchase_date' => $subscriptionRequest->getPurchaseDate(),
            'event' => $subscriptionRequest->getEvent(),
            'provider_code' => $subscriptionRequest->getProviderCode(),
            'user_subscription_id' => $subscriptionRequest->getUserSubscriptionId(),
            'user_product_id' => $subscriptionRequest->getUserSubscriptionProductId(),
        ]);
        //do we need to calculate expired date from now OR from previous one
        $expiredDate = $this->calculateExpiredDate($subscriptionRequest);
        $subscription::update([
            'transaction_id' => $transaction->getId(),
            'status' => SubscriptionStatusesEnum::ACTIVE,
            'expired_date' => $expiredDate
        ]);
    }

    public function unsuccessfulRenewal(SubscriptionRequest $subscriptionRequest): void
    {
        // get Subscription by $userSubscriptionId
        // update subscription status
        $user = User::firstOrFail('user_subscription_id', $subscriptionRequest->getUserSubscriptionId());

        $subscription = $user->getSubscription();//stub for now: search user subscription, if not found throw Exception
        $transaction = Transaction::create([
            'original_transaction_id' => $subscriptionRequest->getOriginTransactionId(),
            'receipt' => $subscriptionRequest->getReceipt(),
            'purchase_date' => $subscriptionRequest->getPurchaseDate(),
            'event' => $subscriptionRequest->getEvent(),
            'provider_code' => $subscriptionRequest->getProviderCode(),
            'user_subscription_id' => $subscriptionRequest->getUserSubscriptionId(),
            'user_product_id' => $subscriptionRequest->getUserSubscriptionProductId(),
        ]);
        $subscription::update([
            'transaction_id' => $transaction->getId(),
            'status' => SubscriptionStatusesEnum::ON_HOLD,//if we get this status - send mail to user about billing issue
        ]);
    }

    public function cancelSubscription(SubscriptionRequest $subscriptionRequest): void
    {
        // get Subscription by $userSubscriptionId
        // update subscription status
        $user = User::firstOrFail('user_subscription_id', $subscriptionRequest->getUserSubscriptionId());

        $subscription = $user->getSubscription();//stub for now: search user subscription, if not found throw Exception
        $transaction = Transaction::create([
            'original_transaction_id' => $subscriptionRequest->getOriginTransactionId(),
            'receipt' => $subscriptionRequest->getReceipt(),
            'purchase_date' => $subscriptionRequest->getPurchaseDate(),
            'event' => $subscriptionRequest->getEvent(),
            'provider_code' => $subscriptionRequest->getProviderCode(),
            'user_subscription_id' => $subscriptionRequest->getUserSubscriptionId(),
            'user_product_id' => $subscriptionRequest->getUserSubscriptionProductId(),
        ]);

        $subscription::update([
            'transaction_id' => $transaction->getId(),
            'status' => SubscriptionStatusesEnum::CANCELED
        ]);
    }

    private function calculateExpiredDate(SubscriptionRequest $subscriptionRequest): string
    {
        //stub for now, Model of possible subscription (monthly, yearly)
        $SubscriptionProduct = SubscriptionProduct::getById($subscriptionRequest->getUserSubscriptionProductId()) ?
            SubscriptionProduct::getById($subscriptionRequest->getUserSubscriptionProductId())->getName()
            : 'monthly';
        $SubscriptionProductPeriod = config('subscription.' . $SubscriptionProduct . '.period');
        $SubscriptionProductCount = config('subscription.' . $SubscriptionProduct . '.value');
        $now = Carbon::now();
        $now->add($SubscriptionProductPeriod, $SubscriptionProductCount);

        return $now->toDateTimeString();
    }
}
