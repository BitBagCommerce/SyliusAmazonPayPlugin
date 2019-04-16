<?php

declare(strict_types=1);

namespace spec\BitBag\SyliusAmazonPayPlugin\Resolver;

use BitBag\SyliusAmazonPayPlugin\Repository\PaymentMethodRepositoryInterface;
use BitBag\SyliusAmazonPayPlugin\Resolver\PaymentMethodResolver;
use BitBag\SyliusAmazonPayPlugin\Resolver\PaymentMethodResolverInterface;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Channel\Model\ChannelInterface;
use Sylius\Component\Core\Model\PaymentMethodInterface;

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
