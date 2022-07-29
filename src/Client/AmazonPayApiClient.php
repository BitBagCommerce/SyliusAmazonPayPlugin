<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
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
