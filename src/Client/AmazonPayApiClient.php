<?php

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
            'sandbox' => $config['environment'] === self::SANDBOX_ENVIRONMENT,
        ]);
    }

    public function getClient(): Client
    {
        return $this->client;
    }
}
