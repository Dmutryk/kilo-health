<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class Transaction
 *
 * @property int $id
 * @property int $original_transaction_id
 * @property string $receipt
 * @property string $purchase_date
 * @property string $event
 * //provider code moved here to add a possibility to change the provider in future
 * @property string $provider_code
 *
 * @package App\Models
 */
class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'original_transaction_id',
        'receipt',
        'purchase_date',
        'event',
        'provider_code',
        'user_subscription_id',
        'user_product_id'
    ];

    /**
     * @return HasOne
     */
    public function subscription()
    {
        return $this->hasOne(Subscription::class, 'transaction_id');
    }
}
