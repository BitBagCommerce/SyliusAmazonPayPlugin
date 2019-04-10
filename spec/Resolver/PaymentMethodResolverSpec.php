<?php

namespace spec\Tierperso\SyliusAmazonPayPlugin\Resolver;

use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Channel\Model\ChannelInterface;
use Sylius\Component\Core\Model\PaymentMethodInterface;
use Tierperso\SyliusAmazonPayPlugin\Repository\PaymentMethodRepositoryInterface;
use Tierperso\SyliusAmazonPayPlugin\Resolver\PaymentMethodResolver;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Tierperso\SyliusAmazonPayPlugin\Resolver\PaymentMethodResolverInterface;

final class PaymentMethodResolverSpec extends ObjectBehavior
{
    function let(
        PaymentMethodRepositoryInterface $paymentMethodRepository,
        ChannelContextInterface $channelContext
    ): void {
        $this->beConstructedWith(
            $paymentMethodRepository,
            $channelContext
        );
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(PaymentMethodResolver::class);
    }

    function it_implements_payment_method_resolver_interface(): void
    {
        $this->shouldImplement(PaymentMethodResolverInterface::class);
    }

    function it_resolves(
        PaymentMethodRepositoryInterface $paymentMethodRepository,
        ChannelContextInterface $channelContext,
        PaymentMethodInterface $paymentMethod,
        ChannelInterface $channel

    ): void {
        $channelContext->getChannel()->willReturn($channel);
        $paymentMethodRepository->findAllEnabledByFactoryNameAndChannel('factoryName', $channel)->willReturn([$paymentMethod]);

        $this->resolvePaymentMethod('factoryName')->shouldReturn($paymentMethod);
    }
}
