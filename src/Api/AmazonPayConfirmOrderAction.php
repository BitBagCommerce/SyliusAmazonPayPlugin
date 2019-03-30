<?php

declare(strict_types=1);

namespace Tierperso\SyliusAmazonPayPlugin\Api;

use AmazonPay\Client;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class AmazonPayConfirmOrderAction
{
    public function __invoke(Request $request): Response
    {
        $client = new Client($this->getConfig());

        $requestParameters = [];
        $requestParameters['mws_auth_token']    = null;
        $requestParameters['amazon_order_reference_id'] = $request->request->get('orderReferenceId');

        $response = $client->confirmOrderReference($requestParameters);

        $responsearray['confirm'] = json_decode($response->toJson());

        if ($client->success) {
            $requestParameters['authorization_amount'] = '19.95';
            $requestParameters['authorization_reference_id'] = uniqid('', false);
            $requestParameters['seller_authorization_note'] = 'Authorizing and capturing the payment';
            $requestParameters['transaction_timeout'] = 0;

            $requestParameters['capture_now'] = false;
            $requestParameters['soft_descriptor'] = null;

            $response = $client->authorize($requestParameters);
            $responsearray['authorize'] = json_decode($response->toJson());
        }
        return JsonResponse::create($responsearray->toArray());
    }

    private function getConfig(): array
    {
        $amazonpayConfig = [
            'merchant_id'   => 'A5445N2KWSM0Z',
            'access_key'    => 'AKIAIK4SK5FO6ZB32ZMQ',
            'secret_key'    => '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',
            'client_id'     => 'amzn1.application-oa2-client.bce33695af0245ceb924af2ede4b9877',
            'region'        => 'de',
            'currency_code' => 'EUR',
            'sandbox'       => true
        ];

        return $amazonpayConfig;
    }
}
