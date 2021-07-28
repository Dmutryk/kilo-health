<?php

namespace App\Providers;

use App\Services\SubscriptionHandler;
use Illuminate\Support\ServiceProvider;
use App\Services\Interfaces\SubscriptionHandlerInterface;

/**
 * Class SubscriptionHandlerProvider
 *
 * @package App\Providers
 */
class SubscriptionHandlerProvider extends ServiceProvider
{
    /**
     * Register Validation service.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(
            SubscriptionHandlerInterface::class,
            function ($app) {
                return new SubscriptionHandler();
            }
        );
    }
}
