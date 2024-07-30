<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusAmazonPayPlugin\Client;

use AmazonPay\Client;
use Exception;
use Sylius\Component\Core\Model\PaymentMethodInterface;

class AmazonPayApiClient implements AmazonPayApiClientInterface
{
    /** @var Client */
    private $client;

    /**
     * @throws Exception
     */
    public function initializeFromPaymentMethod(PaymentMethodInterface $paymentMethod): void
    {
        $config = $paymentMethod->getGatewayConfig()->getConfig();

        $this->initialize($config);
    }

    /**
     * @throws Exception
     */
    public function initialize(array $config): void
    {
        $this->client = new Client([
            'merchant_id' => $config['merchantId'],
            'access_key' => $config['accessKey'],
            'secret_key' => $config['secretKey'],
            'client_id' => $config['clientId'],
            'region' => $config['region'],
            'sandbox' => self::SANDBOX_ENVIRONMENT === $config['environment'],
        ]);
    }

    public function getClient(): Client
    {
        return $this->client;
    }
}
