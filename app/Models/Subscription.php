<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Subscription
 *
 * @property int $id
 * @property int $user_id
 * @property int $transaction_id
 * @property string $status
 * @property string $type
 * @property string $user_subscription_id
 *
 * @package App\Models
 */
class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'transaction_id',
        'status',
        'type',
        'expired_date'
    ];

    /**
     * Could be for example: canceled, initial, etc.
     * Could be move on to separate table SubscriptionStatuses
     *
     * @var string $status
     */
    private string $status;

    /**
     * Could be for example: basic, premium, etc.
     * Could be move on to separate table SubscriptionTypes
     *
     * @var string $type
     */
    private string $type;


    /**
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo
     */
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
