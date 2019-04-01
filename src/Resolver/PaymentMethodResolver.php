<?php

declare(strict_types=1);

namespace Tierperso\SyliusAmazonPayPlugin\Resolver;

use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Core\Model\PaymentMethodInterface;
use Tierperso\SyliusAmazonPayPlugin\Repository\PaymentMethodRepositoryInterface;

final class PaymentMethodResolver implements PaymentMethodResolverInterface
{
    /** @var PaymentMethodRepositoryInterface */
    private $paymentMethodRepository;

    /** @var ChannelContextInterface */
    private $channelContext;

    public function __construct(PaymentMethodRepositoryInterface $paymentMethodRepository, ChannelContextInterface $channelContext)
    {
        $this->paymentMethodRepository = $paymentMethodRepository;
        $this->channelContext = $channelContext;
    }

    public function resolvePaymentMethod(string $factoryName): ?PaymentMethodInterface
    {
        $paymentMethods = $this->paymentMethodRepository->findAllEnabledByFactoryNameAndChannel(
            $factoryName,
            $this->channelContext->getChannel()
        );

        return count($paymentMethods) > 0 ? $paymentMethods[0] : null;
    }
}
