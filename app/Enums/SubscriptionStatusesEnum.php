<?php


namespace App\Enums;


class SubscriptionStatusesEnum extends \SplEnum
{
    const CANCELED = 'Canceled';
    const ACTIVE = 'Active';
    const EXPIRED = 'Expired';
    const ON_HOLD = 'OnHold';
}
