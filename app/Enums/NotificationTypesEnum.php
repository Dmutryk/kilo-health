<?php


namespace App\Enums;


class NotificationTypesEnum extends \SplEnum
{
    const INITIAL_BUY = 'INITIAL_BUY';
    const DID_RENEW = 'DID_RENEW';
    const DID_FAIL_TO_RENEW = 'DID_FAIL_TO_RENEW';
    const CANCEL = 'CANCEL';
}
