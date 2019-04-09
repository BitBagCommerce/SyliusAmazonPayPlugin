<?php

declare(strict_types=1);

namespace Tierperso\SyliusAmazonPayPlugin\Client;

use AmazonPay\Client;
use Sylius\Component\Core\Model\PaymentMethodInterface;

interface AmazonPayApiClientInterface
{
    public const PRODUCTION_ENVIRONMENT = 'production';
    public const SANDBOX_ENVIRONMENT = 'sandbox';

    public function initializeFromPaymentMethod(PaymentMethodInterface $paymentMethod): void;

    public function initialize(array $config): void;

    public function getClient(): Client;
}
