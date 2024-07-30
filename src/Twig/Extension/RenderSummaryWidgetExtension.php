<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusAmazonPayPlugin\Twig\Extension;

use BitBag\SyliusAmazonPayPlugin\AmazonPayGatewayFactory;
use BitBag\SyliusAmazonPayPlugin\Resolver\PaymentMethodResolverInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\PaymentMethod;
use Sylius\Component\Order\Context\CartContextInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class RenderSummaryWidgetExtension extends AbstractExtension
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
        CartContextInterface $cartContext,
    ) {
        $this->templating = $templating;
        $this->paymentMethodResolver = $paymentMethodResolver;
        $this->cartContext = $cartContext;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('bitbag_amazon_pay_render_summary_widget', [$this, 'renderSummaryWidget'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function renderSummaryWidget(): string
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

        return $this->templating->render('@BitBagSyliusAmazonPayPlugin/AmazonPay/Summary/_widget.html.twig', [
            'config' => $config,
            'amazonOrderReferenceId' => $amazonOrderReferenceId,
        ]);
    }
}
