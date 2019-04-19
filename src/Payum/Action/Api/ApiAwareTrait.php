<?php

declare(strict_types=1);

namespace BitBag\SyliusAmazonPayPlugin\Payum\Action\Api;

use BitBag\SyliusAmazonPayPlugin\Client\AmazonPayApiClientInterface;
use Payum\Core\Exception\UnsupportedApiException;

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
