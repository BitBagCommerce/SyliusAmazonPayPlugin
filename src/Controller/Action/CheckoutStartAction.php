<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusAmazonPayPlugin\Controller\Action;

use BitBag\SyliusAmazonPayPlugin\AmazonPayGatewayFactory;
use Doctrine\ORM\EntityManagerInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\PaymentInterface;
use Sylius\Component\Core\Model\PaymentMethod;
use Sylius\Component\Core\Model\PaymentMethodInterface;
use Sylius\Component\Order\Context\CartContextInterface;
use Sylius\Component\Payment\Resolver\PaymentMethodsResolverInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;

final class CheckoutStartAction
{
    /** @var CartContextInterface */
    private $cartContext;

    /** @var EntityManagerInterface */
    private $orderEntityManager;

    /** @var RouterInterface */
    private $router;

    /** @var PaymentMethodsResolverInterface */
    private $paymentMethodsResolver;

    public function __construct(
        CartContextInterface $cartContext,
        EntityManagerInterface $orderEntityManager,
        RouterInterface $router,
        PaymentMethodsResolverInterface $paymentMethodsResolver
    ) {
        $this->cartContext = $cartContext;
        $this->orderEntityManager = $orderEntityManager;
        $this->router = $router;
        $this->paymentMethodsResolver = $paymentMethodsResolver;
    }

    public function __invoke(Request $request): Response
    {
        /** @var OrderInterface $order */
        $order = $this->cartContext->getCart();

        /** @var PaymentInterface $order */
        $payment = $order->getLastPayment();

        if(null !== $payment) {
            /** @var PaymentMethodInterface $paymentMethod */
            $paymentMethod = $payment->getMethod();

            if (
                null !== $paymentMethod &&
                AmazonPayGatewayFactory::FACTORY_NAME === $paymentMethod->getGatewayConfig()->getFactoryName()
            ) {
                /** @var PaymentMethod[] $paymentMethods */
                $paymentMethods = $this->paymentMethodsResolver->getSupportedMethods($order->getLastPayment());

                foreach ($paymentMethods as $paymentMethod) {
                    if (
                        AmazonPayGatewayFactory::FACTORY_NAME !== $paymentMethod->getGatewayConfig()->getFactoryName()
                    ) {
                        $order->getLastPayment()->setMethod($paymentMethod);

                        $this->orderEntityManager->flush();

                        return new RedirectResponse($this->router->generate('sylius_shop_checkout_address'));
                    }
                }
            }
        }

        return new RedirectResponse($this->router->generate('sylius_shop_checkout_address'));
    }
}
