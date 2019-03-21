<?php

declare(strict_types=1);

namespace Tierperso\SyliusAmazonPayPlugin\Client;

use AmazonPay\ClientInterface;

class AmazonPayApiClient
{
    /** @var ClientInterface */
    protected $apiClient;

    /** @var string */
    protected $environment;

    /** @var string */
    protected $merchant_id;

    /** @var string */
    protected $access_key;

    /** @var string */
    protected $secret_key;

    /** @var string */
    protected $client_id;

    /** @var string */
    protected $region;

    public function __construct(ClientInterface $client)
    {
        $this->apiClient = $client;
    }

    public function setConfig(
        string $environment,
        string $merchant_id,
        string $access_key,
        string $secret_key,
        string $client_id,
        string $region
    ): void {
        $this->environment = $environment;
        $this->merchant_id = $merchant_id;
        $this->access_key = $access_key;
        $this->secret_key = $secret_key;
        $this->client_id = $client_id;
        $this->region = $region;
    }
}
