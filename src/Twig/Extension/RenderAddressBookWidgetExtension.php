<?php

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

final class RenderAddressBookWidgetExtension extends AbstractExtension
{
    /** @var EngineInterface */
    private $templatingEngine;

    /** @var PaymentMethodResolverInterface */
    private $paymentMethodResolver;

    /** @var CartContextInterface */
    private $cartContext;

    public function __construct(
        EngineInterface $templatingEngine,
        PaymentMethodResolverInterface $paymentMethodResolver,
        CartContextInterface $cartContext
    ) {
        $this->templatingEngine = $templatingEngine;
        $this->paymentMethodResolver = $paymentMethodResolver;
        $this->cartContext = $cartContext;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('bitbag_amazon_pay_render_address_book_widget', [$this, 'renderAddressBookWidget'], ['is_safe' => ['html']]),
        ];
    }

    public function renderAddressBookWidget(): string
    {
        /** @var OrderInterface $order */
        $order = $this->cartContext->getCart();

        /** @var PaymentMethod $paymentMethodCurrent */
        $paymentMethodCurrent = $order->getLastPayment()->getMethod();

        if (
            null === $paymentMethodCurrent ||
            AmazonPayGatewayFactory::FACTORY_NAME !== $paymentMethodCurrent->getGatewayConfig()->getFactoryName()
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

        return $this->templatingEngine->render('BitBagSyliusAmazonPayPlugin:AmazonPay/AddressBook:_widget.html.twig', [
            'config' => $config,
            'amazonOrderReferenceId' => $amazonOrderReferenceId,
        ]);
    }
}
