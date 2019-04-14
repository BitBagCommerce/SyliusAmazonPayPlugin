<?php

declare(strict_types=1);

namespace Tierperso\SyliusAmazonPayPlugin\Resolver;

use Sylius\Component\Core\Model\PaymentInterface;

interface PaymentStateResolverInterface
{
    public function resolve(PaymentInterface $payment): void;
}
