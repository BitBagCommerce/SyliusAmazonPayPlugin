<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusAmazonPayPlugin\Resolver;

use BitBag\SyliusAmazonPayPlugin\Repository\PaymentMethodRepositoryInterface;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Core\Model\PaymentMethodInterface;

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
