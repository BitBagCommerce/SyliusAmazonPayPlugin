<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
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
