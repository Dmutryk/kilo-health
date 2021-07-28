<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionRequest extends Model
{
    private string $originTransactionId;
    private string $receipt;
    private string $purchaseDate;
    private string $event;
    private string $providerCode;
    private string $userSubscriptionId;
    private string $userSubscriptionProductId; // id of the subscription type (e.g. monthly, yearly) for future

    /**
     * @return string
     */
    public function getUserSubscriptionProductId(): string
    {
        return $this->userSubscriptionProductId;
    }

    /**
     * @param string $userSubscriptionProductId
     */
    public function setUserSubscriptionProductId(string $userSubscriptionProductId): void
    {
        $this->userSubscriptionProductId = $userSubscriptionProductId;
    }

    /**
     * @return string
     */
    public function getUserSubscriptionId(): string
    {
        return $this->userSubscriptionId;
    }

    /**
     * @param string $userSubscriptionId
     */
    public function setUserSubscriptionId(string $userSubscriptionId): void
    {
        $this->userSubscriptionId = $userSubscriptionId;
    }

    /**
     * @return string
     */
    public function getOriginTransactionId(): string
    {
        return $this->originTransactionId;
    }

    /**
     * @param string $originTransactionId
     */
    public function setOriginTransactionId(string $originTransactionId): void
    {
        $this->originTransactionId = $originTransactionId;
    }

    /**
     * @return string
     */
    public function getReceipt(): string
    {
        return $this->receipt;
    }

    /**
     * @param string $receipt
     */
    public function setReceipt(string $receipt): void
    {
        $this->receipt = $receipt;
    }

    /**
     * @return string
     */
    public function getPurchaseDate(): string
    {
        return $this->purchaseDate;
    }

    /**
     * @param string $purchaseDate
     */
    public function setPurchaseDate(string $purchaseDate): void
    {
        $this->purchaseDate = $purchaseDate;
    }

    /**
     * @return string
     */
    public function getEvent(): string
    {
        return $this->event;
    }

    /**
     * @param string $event
     */
    public function setEvent(string $event): void
    {
        $this->event = $event;
    }

    /**
     * @return string
     */
    public function getProviderCode(): string
    {
        return $this->providerCode;
    }

    /**
     * @param string $providerCode
     */
    public function setProviderCode(string $providerCode): void
    {
        $this->providerCode = $providerCode;
    }
}
