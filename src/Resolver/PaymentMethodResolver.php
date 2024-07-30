<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
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
            $this->channelContext->getChannel(),
        );

        return 0 < count($paymentMethods) ? $paymentMethods[0] : null;
    }
}
