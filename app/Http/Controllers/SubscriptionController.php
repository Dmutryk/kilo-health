<?php

namespace App\Http\Controllers;

use App\Enums\ProvidersEnum;
use App\Exceptions\PaymentProviderNotFound;
use App\Exceptions\SubscriptionMethodNotFound;
use App\Services\Interfaces\SubscriptionHandlerInterface;
use App\Services\Interfaces\SubscriptionRequestCreatorInterface;
use App\Services\SubscriptionRequestCreators\ApplePaySubscriptionRequestCreator;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SubscriptionController extends Controller
{
    public function __invoke(
        Request $request,
        string $provider,
        SubscriptionHandlerInterface $subscriptionHandler)
    : JsonResponse {
        try {
            // Get SubscriptionBuilder depending on $provider
            $subscriptionRequestCreator = $this->getSubscriptionCreator($provider);
            // build SubscriptionRequest object with filled fields
            $subscriptionRequest = $subscriptionRequestCreator->create($request);
            $subscriptionHandler->handle($subscriptionRequest);
            return response()->json(
                ['status' => 'success'], 200, ['Content-Type' => 'application/json']
            );
        } catch (PaymentProviderNotFound|SubscriptionMethodNotFound $exception) {
            return response()->json(
                ['error' => $exception->getMessage()], 406, ['Content-Type' => 'application/json']
            );
        } catch (\Exception $exception) {
            return response()->json(
                [], 500, ['Content-Type' => 'application/json']
            );
        }
    }

    private function getSubscriptionCreator(string $provider): SubscriptionRequestCreatorInterface
    {
        switch ($provider) {
            case ProvidersEnum::APPLE_PAY:
                return new ApplePaySubscriptionRequestCreator();
            //TODO add new payment providers here
        }

        throw new PaymentProviderNotFound('Payment provider not found.');
    }
}
