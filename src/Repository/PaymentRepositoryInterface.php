<?php

declare(strict_types=1);

namespace Tierperso\SyliusAmazonPayPlugin\Repository;

use Sylius\Component\Core\Repository\PaymentRepositoryInterface as BasePaymentRepositoryInterface;

interface PaymentRepositoryInterface extends BasePaymentRepositoryInterface
{
    public function findAllByGatewayFactoryNameAndState(string $gatewayFactoryName, string $state): array;
}
