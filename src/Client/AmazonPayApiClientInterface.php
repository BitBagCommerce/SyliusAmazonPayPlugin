<?php

declare(strict_types=1);

namespace Tierperso\SyliusAmazonPayPlugin\Client;

interface AmazonPayApiClientInterface
{
    public function setConfig(
        string $environment,
        string $merchant_id,
        string $access_key,
        string $secret_key,
        string $client_id,
        string $region
    ): void;

    public function getConfig(): array;
}
