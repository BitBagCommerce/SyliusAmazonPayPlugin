<?php

declare(strict_types=1);

namespace Tierperso\SyliusAmazonPayPlugin\Repository;

use Sylius\Component\Channel\Model\ChannelInterface;
use Sylius\Component\Core\Repository\PaymentMethodRepositoryInterface as BasePaymentMethodRepositoryInterface;

interface PaymentMethodRepositoryInterface extends BasePaymentMethodRepositoryInterface
{
    public function findAllEnabledByFactoryNameAndChannel(string $name, ChannelInterface $channel): array;
}
