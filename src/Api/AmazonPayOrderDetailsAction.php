<?php

declare(strict_types=1);

namespace Tierperso\SyliusAmazonPayPlugin\Api;

use AmazonPay\Client;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class AmazonPayOrderDetailsAction
{
    public function __invoke(Request $request): Response
    {
        $client = new Client($this->getConfig());

        $requestParameters = [];
        $requestParameters['amount']            = '19.95';
        $requestParameters['currency_code']     = $this->getConfig()['currency_code'];
        $requestParameters['seller_note']       = 'Testing PHP SDK Samples';
        $requestParameters['seller_order_id']   = '123456-TestOrder-123456';
        $requestParameters['store_name']        = 'SDK Sample Store Name';
        $requestParameters['custom_information']= 'Any custom information';
        $requestParameters['mws_auth_token']    = null;
        $requestParameters['amazon_order_reference_id'] = $request->request->get('orderReferenceId');

        $response = $client->setOrderReferenceDetails($requestParameters);

        if ($client->success) {
            $requestParameters['access_token'] = $request->request->get('accessToken');
            $response = $client->getOrderReferenceDetails($requestParameters);
        }

        $request->getSession()->set('amazon_order_reference_id', $request->request->get('orderReferenceId'));

        return JsonResponse::create($response->toArray());
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
