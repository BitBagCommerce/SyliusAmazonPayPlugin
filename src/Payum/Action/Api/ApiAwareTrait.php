<?php

declare(strict_types=1);

namespace Tierperso\SyliusAmazonPayPlugin\Payum\Action\Api;

use Payum\Core\Exception\UnsupportedApiException;
use Tierperso\SyliusAmazonPayPlugin\Client\AmazonPayApiClientInterface;

trait ApiAwareTrait
{
    /** @var AmazonPayApiClientInterface */
    protected $amazonPayApiClient;

    public function setApi($amazonPayApiClient): void
    {
        if (!$amazonPayApiClient instanceof AmazonPayApiClientInterface) {
            throw new UnsupportedApiException('Not supported.Expected an instance of ' . AmazonPayApiClientInterface::class);
        }
        $this->amazonPayApiClient = $amazonPayApiClient;
    }
}
