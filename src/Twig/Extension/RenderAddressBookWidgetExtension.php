<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusAmazonPayPlugin\Twig\Extension;

use BitBag\SyliusAmazonPayPlugin\AmazonPayGatewayFactory;
use BitBag\SyliusAmazonPayPlugin\Resolver\PaymentMethodResolverInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\PaymentMethod;
use Sylius\Component\Order\Context\CartContextInterface;
use Symfony\Component\Templating\EngineInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

final class RenderAddressBookWidgetExtension extends AbstractExtension
{
    /** @var Environment */
    private $templating;

    /** @var PaymentMethodResolverInterface */
    private $paymentMethodResolver;

    /** @var CartContextInterface */
    private $cartContext;

    public function __construct(
        Environment $templating,
        PaymentMethodResolverInterface $paymentMethodResolver,
        CartContextInterface $cartContext
    ) {
        $this->templating = $templating;
        $this->paymentMethodResolver = $paymentMethodResolver;
        $this->cartContext = $cartContext;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('bitbag_amazon_pay_render_address_book_widget', [$this, 'renderAddressBookWidget'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function renderAddressBookWidget(): string
    {
        /** @var OrderInterface $order */
        $order = $this->cartContext->getCart();

        /** @var PaymentMethod $paymentMethodCurrent */
        $paymentMethodCurrent = $order->getLastPayment()->getMethod();

        if (
            null === $paymentMethodCurrent ||
            !isset($paymentMethodCurrent->getGatewayConfig()->getConfig()['type']) ||
            AmazonPayGatewayFactory::FACTORY_NAME !== $paymentMethodCurrent->getGatewayConfig()->getConfig()['type']
        ) {
            return '';
        }

        $paymentMethod = $this->paymentMethodResolver->resolvePaymentMethod(AmazonPayGatewayFactory::FACTORY_NAME);

        if (null === $paymentMethod) {
            return '';
        }

        $config = $paymentMethod->getGatewayConfig()->getConfig();

        $paymentDetails = $order->getLastPayment()->getDetails();

        $amazonOrderReferenceId = null;

        if (isset($paymentDetails['amazon_pay']['amazon_order_reference_id'])) {
            $amazonOrderReferenceId = $paymentDetails['amazon_pay']['amazon_order_reference_id'];
        }

        return $this->templating->render('@BitBagSyliusAmazonPayPlugin/AmazonPay/AddressBook/_widget.html.twig', [
            'config' => $config,
            'amazonOrderReferenceId' => $amazonOrderReferenceId,
        ]);
    }
}
