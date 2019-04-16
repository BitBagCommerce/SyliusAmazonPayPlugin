<?php

declare(strict_types=1);

namespace spec\BitBag\SyliusAmazonPayPlugin\Client;

use AmazonPay\Client;
use BitBag\SyliusAmazonPayPlugin\Client\AmazonPayApiClient;
use BitBag\SyliusAmazonPayPlugin\Client\AmazonPayApiClientInterface;
use Payum\Core\Model\GatewayConfigInterface;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Core\Model\PaymentMethodInterface;
use Prophecy\Argument;

final class AmazonPayApiClientSpec extends ObjectBehavior
{
    function it_is_initializable(): void
    {
        $this->shouldHaveType(AmazonPayApiClient::class);
    }

    function it_implements_amazon_pay_api_client_interface(): void
    {
        $this->shouldImplement(AmazonPayApiClientInterface::class);
    }

    function it_initializes(): void
    {
        $this->initialize([
            'merchantId' => 'merchantId',
            'accessKey' => 'accessKey',
            'secretKey' => 'secretKey',
            'clientId' => 'clientId',
            'region' => 'region',
            'environment' => 'sandbox'
        ]);
    }

    function it_initializes_from_payment_method(
        PaymentMethodInterface $paymentMethod,
        GatewayConfigInterface $gatewayConfig
    ): void {
        $gatewayConfig->getConfig()->willReturn([
            'merchantId' => 'merchantId',
            'accessKey' => 'accessKey',
            'secretKey' => 'secretKey',
            'clientId' => 'clientId',
            'region' => 'region',
            'environment' => 'sandbox'
        ]);
        $paymentMethod->getGatewayConfig()->willReturn($gatewayConfig);

        $this->initialize([
            'merchantId' => 'merchantId',
            'accessKey' => 'accessKey',
            'secretKey' => 'secretKey',
            'clientId' => 'clientId',
            'region' => 'region',
            'environment' => 'sandbox'
        ]);

        $this->initializeFromPaymentMethod($paymentMethod);
    }
}
