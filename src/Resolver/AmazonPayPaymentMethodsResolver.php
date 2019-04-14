<?php

declare(strict_types=1);

namespace Tierperso\SyliusAmazonPayPlugin\Resolver;

use Sylius\Component\Core\Model\PaymentInterface;
use Sylius\Component\Core\Model\PaymentMethod;
use Sylius\Component\Core\Repository\PaymentMethodRepositoryInterface;
use Sylius\Component\Payment\Model\PaymentInterface as BasePaymentInterface;
use Sylius\Component\Payment\Resolver\PaymentMethodsResolverInterface;
use Tierperso\SyliusAmazonPayPlugin\AmazonPayGatewayFactory;
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
        /** @var PaymentInterface $payment */
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
            AmazonPayGatewayFactory::FACTORY_NAME !== $method->getGatewayConfig()->getFactoryName()
        ;
    }
}
