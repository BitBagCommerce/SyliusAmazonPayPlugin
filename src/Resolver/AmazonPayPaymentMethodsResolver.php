<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusAmazonPayPlugin\Resolver;

use BitBag\SyliusAmazonPayPlugin\AmazonPayGatewayFactory;
use Sylius\Component\Core\Model\PaymentInterface;
use Sylius\Component\Core\Model\PaymentMethod;
use Sylius\Component\Core\Repository\PaymentMethodRepositoryInterface;
use Sylius\Component\Payment\Model\PaymentInterface as BasePaymentInterface;
use Sylius\Component\Payment\Resolver\PaymentMethodsResolverInterface;
use Webmozart\Assert\Assert;

final class AmazonPayPaymentMethodsResolver implements PaymentMethodsResolverInterface
{
    /** @var PaymentMethodRepositoryInterface */
    private $paymentMethodRepository;

    public function __construct(PaymentMethodRepositoryInterface $paymentMethodRepository)
    {
        $this->paymentMethodRepository = $paymentMethodRepository;
    }

    public function getSupportedMethods(BasePaymentInterface $payment): array
    {
        Assert::isInstanceOf($payment, PaymentInterface::class);
        Assert::true($this->supports($payment), 'This payment method is not support by resolver');

        /** @var PaymentMethod[] $paymentMethods */
        $paymentMethods = $this->paymentMethodRepository->findEnabledForChannel($payment->getOrder()->getChannel());

        $supportedMethods = [];

        foreach ($paymentMethods as $paymentMethod) {
            if (AmazonPayGatewayFactory::FACTORY_NAME !== $paymentMethod->getGatewayConfig()->getFactoryName()) {
                $supportedMethods[] = $paymentMethod;
            }
        }

        return $supportedMethods;
    }

    public function supports(BasePaymentInterface $payment): bool
    {
        /** @var PaymentMethod $method */
        $method = $payment->getMethod();

        return $payment instanceof PaymentInterface &&
            null !== $payment->getOrder() &&
            null !== $payment->getOrder()->getChannel() &&
            null !== $method &&
            (AmazonPayGatewayFactory::FACTORY_NAME !== $method->getGatewayConfig()->getFactoryName() || $payment->getOrder()->isCheckoutCompleted())
        ;
    }
}
