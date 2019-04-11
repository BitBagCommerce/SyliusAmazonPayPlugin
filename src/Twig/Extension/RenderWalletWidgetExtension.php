<?php

declare(strict_types=1);

namespace BitBag\SyliusAmazonPayPlugin\Twig\Extension;

use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Order\Context\CartContextInterface;
use BitBag\SyliusAmazonPayPlugin\AmazonPayGatewayFactory;
use Twig\Extension\AbstractExtension;
use Symfony\Component\Templating\EngineInterface;
use BitBag\SyliusAmazonPayPlugin\Resolver\PaymentMethodResolverInterface;
use Twig\TwigFunction;

final class RenderWalletWidgetExtension extends AbstractExtension
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
            new TwigFunction('bitbag_amazon_pay_render_wallet_widget', [$this, 'renderWalletWidget'], ['is_safe' => ['html']]),
        ];
    }

    public function renderWalletWidget(): string
    {
        $paymentMethod = $this->paymentMethodResolver->resolvePaymentMethod(AmazonPayGatewayFactory::FACTORY_NAME);

        if (null === $paymentMethod) {
            return '';
        }

        $config = $paymentMethod->getGatewayConfig()->getConfig();

        /** @var OrderInterface $order */
        $order = $this->cartContext->getCart();

        $paymentDetails = $order->getLastPayment()->getDetails();

        $amazonOrderReferenceId = null;

        if (isset($paymentDetails['amazon_pay']['amazon_order_reference_id'])) {
            $amazonOrderReferenceId = $paymentDetails['amazon_pay']['amazon_order_reference_id'];
        }

        return $this->templatingEngine->render('BitBagSyliusAmazonPayPlugin:AmazonPay/Wallet:_widget.html.twig', [
            'config' => $config,
            'amazonOrderReferenceId' => $amazonOrderReferenceId,
        ]);
    }
}
