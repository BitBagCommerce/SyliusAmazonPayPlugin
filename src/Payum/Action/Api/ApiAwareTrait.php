<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
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
