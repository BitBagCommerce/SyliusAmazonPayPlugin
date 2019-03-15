<?php

declare(strict_types=1);

namespace Tierperso\SyliusAmazonPayPlugin\Request\Api;

use AmazonPay\Client;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class AmazonPayOrderDetails
{
    public function getAmazonOrderDetails(): object
    {
        $client = new Client($this->getConfig());
        $requestParameters = [];

        $requestParameters['amount']            = '19.95';
        $requestParameters['currencyCode']     = $this->getConfig()['currencyCode'];
        $requestParameters['sellerNote']       = 'Testing PHP SDK Samples';
        $requestParameters['sellerOrderId']   = '123456-TestOrder-123456';
        $requestParameters['storeName']        = 'SDK Sample Store Name';
        $requestParameters['customInformation']= 'Any custom information';
        $requestParameters['mwsAuthorizationToken']    = null;
        $requestParameters['amazonOrderReferenceId'] = $_POST['orderReferenceId'];

        $response = $client->setOrderReferenceDetails($requestParameters);

        if ($client->success)
        {
            $requestParameters['accessToken'] = $_POST['accessToken'];
            $response = $client->getOrderReferenceDetails($requestParameters);
        }
        $_SESSION['amazonOrderReferenceId'] = $_POST['orderReferenceId'];

        $json = json_decode($response->toJson());

        return new JsonResponse($json);
    }

    private function getConfig(): array
    {
        $amazonpay_config = [
            'merchantId'   => 'A5445N2KWSM0Z',
            'accessKey'    => 'AKIAIK4SK5FO6ZB32ZMQ',
            'secretKey'    => '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',
            'clientId'     => 'amzn1.application-oa2-client.bce33695af0245ceb924af2ede4b9877',
            'region'        => 'de',
            'currencyCode' => 'EUR',
            'sandbox'       => true
        ];

        return $amazonpay_config;
    }
}
