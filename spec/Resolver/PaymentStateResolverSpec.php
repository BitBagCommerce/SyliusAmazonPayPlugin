<?php

declare(strict_types=1);

namespace spec\BitBag\SyliusAmazonPayPlugin\Resolver;

use AmazonPay\Client;
use AmazonPay\ResponseParser;
use BitBag\SyliusAmazonPayPlugin\Client\AmazonPayApiClientInterface;
use BitBag\SyliusAmazonPayPlugin\Resolver\PaymentStateResolver;
use BitBag\SyliusAmazonPayPlugin\Resolver\PaymentStateResolverInterface;
use Doctrine\ORM\EntityManagerInterface;
use PhpSpec\ObjectBehavior;
use SM\Factory\FactoryInterface;
use Sylius\Component\Core\Model\PaymentInterface;
use Sylius\Component\Core\Model\PaymentMethodInterface;

final class PaymentStateResolverSpec extends ObjectBehavior
{
    function let(
        FactoryInterface $stateMachineFactory,
        AmazonPayApiClientInterface $amazonPayApiClient,
        EntityManagerInterface $paymentEntityManager
    ): void {
        $this->beConstructedWith(
            $stateMachineFactory,
            $amazonPayApiClient,
            $paymentEntityManager
        );
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(PaymentStateResolver::class);
    }

    function it_implements_payment_state_resolver_interface(): void
    {
        $this->shouldImplement(PaymentStateResolverInterface::class);
    }

    function it_resolves(
        PaymentInterface $payment,
        PaymentMethodInterface $paymentMethod,
        AmazonPayApiClientInterface $amazonPayApiClient,
        Client $client,
        EntityManagerInterface $paymentEntityManager,
        ResponseParser $parser
    ): void {

        $payment->getMethod()->willReturn($paymentMethod);

        $amazonPayApiClient->initializeFromPaymentMethod($paymentMethod)->shouldBeCalled();

        $payment->getDetails()->willReturn([
            'amazon_pay' => [
                'amazon_authorization_id' => '321',
            ],
        ]);

        $client->getAuthorizationDetails([
            'amazon_authorization_id' => '321',
        ])->willReturn($parser);

        $amazonPayApiClient->getClient()->willReturn($client);

        $paymentEntityManager->flush()->shouldBeCalled();
        $this->resolve($payment);
    }
}
