<?php

declare(strict_types=1);

namespace Tierperso\SyliusAmazonPayPlugin\Twig\Extension;

use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\PaymentMethod;
use Sylius\Component\Order\Context\CartContextInterface;
use Tierperso\SyliusAmazonPayPlugin\AmazonPayGatewayFactory;
use Twig\Extension\AbstractExtension;
use Symfony\Component\Templating\EngineInterface;
use Tierperso\SyliusAmazonPayPlugin\Resolver\PaymentMethodResolverInterface;
use Twig\TwigFunction;

final class RenderSummaryWidgetExtension extends AbstractExtension
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
            new TwigFunction('tierperso_amazon_pay_render_summary_widget', [$this, 'renderSummaryWidget'], ['is_safe' => ['html']]),
        ];
    }

    public function renderSummaryWidget(): string
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

        return $this->templatingEngine->render('TierpersoSyliusAmazonPayPlugin:AmazonPay/Summary:_widget.html.twig', [
            'config' => $config,
            'amazonOrderReferenceId' => $amazonOrderReferenceId,
        ]);
    }
}
