<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusAmazonPayPlugin;

use BitBag\SyliusAmazonPayPlugin\Client\AmazonPayApiClient;
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
                'merchantId' => null,
                'accessKey' => null,
                'secretKey' => null,
                'clientId' => null,
                'region' => null,
            ];

            $config->defaults($config['payum.default_options']);

            $config['payum.required_options'] = [
                'environment',
                'merchantId',
                'accessKey',
                'secretKey',
                'clientId',
                'region',
            ];

            $config['payum.api'] = function (ArrayObject $config): AmazonPayApiClient {
                $config->validateNotEmpty($config['payum.required_options']);

                /** @var AmazonPayApiClient $amazonPayApiClient */
                $amazonPayApiClient = $config['payum.http_client'];

                $amazonPayApiClient->initialize($config->getArrayCopy());

                return $amazonPayApiClient;
            };
        }
    }
}
