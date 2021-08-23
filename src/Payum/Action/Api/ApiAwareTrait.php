<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

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
