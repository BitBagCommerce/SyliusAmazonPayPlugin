<?php

declare(strict_types=1);

namespace Tierperso\SyliusAmazonPayPlugin;

use Tierperso\SyliusAmazonPayPlugin\Client\AmazonPayApiClient;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\GatewayFactory;

final class AmazonPayGatewayFactory extends GatewayFactory
{
    public const FACTORY_NAME = 'amazonpay';

    protected function populateConfig(ArrayObject $config): void
    {
        $config->defaults([
            'payum.factory_name' => self::FACTORY_NAME,
            'payum.factory_title' => 'AmazonPay',
        ]);

        if (false === (bool) $config['payum.api']) {
            $config['payum.default_options'] = [
                'environment' => 'sandbox',
                'merchant_id' => null,
                'access_key' => null,
                'secret_key' => null,
                'client_id' => null,
                'region' => null,
            ];

            $config->defaults($config['payum.default_options']);

            $config['payum.required_options'] = [
                'environment',
                'merchant_id',
                'access_key',
                'secret_key',
                'client_id',
                'region',
            ];

            $config['payum.api'] = function (ArrayObject $config): AmazonPayApiClient {
                $config->validateNotEmpty($config['payum.required_options']);

                /** @var AmazonPayApiClient $amazonPayApiClient */
                $amazonPayApiClient = $config['payum.http_client'];

                $amazonPayApiClient->setConfig(
                    $config['environment'],
                    $config['merchant_id'],
                    $config['access_key'],
                    $config['secret_key'],
                    $config['client_id'],
                    $config['region']
                );

                return $amazonPayApiClient;
            };
        }
    }
}
