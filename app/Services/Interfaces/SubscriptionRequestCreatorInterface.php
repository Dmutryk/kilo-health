<?php

namespace App\Services\Interfaces;

use App\Models\SubscriptionRequest;
use Illuminate\Http\Request;

interface SubscriptionRequestCreatorInterface
{
    public function create(Request $request): SubscriptionRequest;
}
