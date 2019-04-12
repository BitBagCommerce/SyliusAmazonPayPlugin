<?php

declare(strict_types=1);

namespace spec\BitBag\SyliusAmazonPayPlugin\Twig\Extension;

use BitBag\SyliusAmazonPayPlugin\Resolver\PaymentMethodResolverInterface;
use BitBag\SyliusAmazonPayPlugin\Twig\Extension\RenderSummaryWidgetExtension;
use Payum\Core\Model\GatewayConfigInterface;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\PaymentMethodInterface;
use Sylius\Component\Core\Model\PaymentInterface;
use Sylius\Component\Order\Context\CartContextInterface;
use Symfony\Component\Templating\EngineInterface;

final class RenderSummaryWidgetExtensionSpec extends ObjectBehavior
{
    function let(
        EngineInterface $templatingEngine,
        PaymentMethodResolverInterface $paymentMethodResolver,
        CartContextInterface $cartContext
    ): void {
        $this->beConstructedWith($templatingEngine, $paymentMethodResolver, $cartContext);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(RenderSummaryWidgetExtension::class);
    }

    function it_extends_twig_extension(): void
    {
        $this->shouldHaveType(\Twig_Extension::class);
    }

    function it_returns_functions(): void
    {
        $functions = $this->getFunctions();
        $functions->shouldHaveCount(1);
        foreach ($functions as $function) {
            $function->shouldHaveType(\Twig_SimpleFunction::class);
        }
    }

    function it_returns_empty_string_if_no_payment_method(
        PaymentMethodResolverInterface $paymentMethodResolver): void
    {
        $paymentMethodResolver->resolvePaymentMethod('amazonpay')->willReturn(null);

        $this->renderSummaryWidget()->shouldReturn('');
    }

    function it_renders_address_book_widget(
        PaymentMethodResolverInterface $paymentMethodResolver,
        PaymentMethodInterface $paymentMethod,
        CartContextInterface $cartContext,
        OrderInterface $order,
        EngineInterface $templatingEngine,
        PaymentInterface $payment,
        GatewayConfigInterface $gatewayConfig
    ): void {
        $gatewayConfig->getConfig()->willReturn([]);
        $paymentMethod->getGatewayConfig()->willReturn($gatewayConfig);
        $paymentMethodResolver->resolvePaymentMethod('amazonpay')->willReturn($paymentMethod);

        $cartContext->getCart()->willReturn($order);

        $payment->getDetails()->willReturn(['amazon_pay' => [
            'amazon_order_reference_id' => 123
        ]]);
        $order->getLastPayment()->willReturn($payment);

        $templatingEngine->render('BitBagSyliusAmazonPayPlugin:AmazonPay/Summary:_widget.html.twig', [
            'config' => [], 'amazonOrderReferenceId' => 123])->willReturn('content');

        $this->renderSummaryWidget()->shouldReturn('content');
    }
}
