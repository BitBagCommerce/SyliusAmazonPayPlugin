<?php

declare(strict_types=1);

namespace BitBag\SyliusAmazonPayPlugin\Resolver;

use Sylius\Component\Core\Model\PaymentMethodInterface;

interface PaymentMethodResolverInterface
{
    public function resolvePaymentMethod(string $factoryName): ?PaymentMethodInterface;
}
