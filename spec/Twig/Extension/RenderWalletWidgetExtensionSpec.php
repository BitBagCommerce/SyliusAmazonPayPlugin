<?php

declare(strict_types=1);

namespace spec\BitBag\SyliusAmazonPayPlugin\Twig\Extension;

use BitBag\SyliusAmazonPayPlugin\Resolver\PaymentMethodResolverInterface;
use BitBag\SyliusAmazonPayPlugin\Twig\Extension\RenderWalletWidgetExtension;
use Payum\Core\Model\GatewayConfigInterface;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\PaymentInterface;
use Sylius\Component\Core\Model\PaymentMethodInterface;
use Sylius\Component\Order\Context\CartContextInterface;
use Symfony\Component\Templating\EngineInterface;

final class RenderWalletWidgetExtensionSpec extends ObjectBehavior
{
    function let(
        EngineInterface $templatingEngine,
        PaymentMethodResolverInterface $paymentMethodResolver,
        CartContextInterface $cartContext
    ): void {
        $this->beConstructedWith($templatingEngine, $paymentMethodResolver, $cartContext);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(RenderWalletWidgetExtension::class);
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

    function it_returns_empty_string_on_null_payment_method_current(
        CartContextInterface $cartContext,
        OrderInterface $order,
        PaymentInterface $payment
    ): void {
        $cartContext->getCart()->willReturn($order);

        $payment->getMethod()->willReturn(null);
        $order->getLastPayment()->willReturn($payment);

        $this->renderWalletWidget()->shouldReturn('');
    }

    function it_returns_empty_string_on_null_payment_method(
        CartContextInterface $cartContext,
        OrderInterface $order,
        PaymentMethodInterface $paymentMethod,
        PaymentInterface $payment,
        PaymentMethodResolverInterface $paymentMethodResolver,
        GatewayConfigInterface $gatewayConfig
    ): void {
        $cartContext->getCart()->willReturn($order);
        $payment->getMethod()->willReturn($paymentMethod);
        $order->getLastPayment()->willReturn($payment);

        $gatewayConfig->getConfig()->willReturn('amazonpay');
        $paymentMethod->getGatewayConfig()->willReturn($gatewayConfig);

        $gatewayConfig->getConfig()->willReturn([]);
        $paymentMethod->getGatewayConfig()->willReturn($gatewayConfig);
        $paymentMethodResolver->resolvePaymentMethod('amazonpay')->willReturn(null);

        $this->renderWalletWidget()->shouldReturn('');
    }

    function it_renders_wallet_widget(
        PaymentMethodResolverInterface $paymentMethodResolver,
        PaymentMethodInterface $paymentMethod,
        PaymentInterface $payment,
        CartContextInterface $cartContext,
        OrderInterface $order,
        EngineInterface $templatingEngine,
        GatewayConfigInterface $gatewayConfig
    ): void {
        $cartContext->getCart()->willReturn($order);

        $payment->getMethod()->willReturn($paymentMethod);
        $order->getLastPayment()->willReturn($payment);

        $gatewayConfig->getFactoryName()->willReturn('amazonpay');
        $paymentMethod->getGatewayConfig()->willReturn($gatewayConfig);

        $gatewayConfig->getConfig()->willReturn([]);
        $paymentMethod->getGatewayConfig()->willReturn($gatewayConfig);
        $paymentMethodResolver->resolvePaymentMethod('amazonpay')->willReturn($paymentMethod);

        $payment->getDetails()->willReturn(['amazon_pay' => [
            'amazon_order_reference_id' => 123,
        ]]);
        $order->getLastPayment()->willReturn($payment);

        $templatingEngine->render('BitBagSyliusAmazonPayPlugin:AmazonPay/Wallet:_widget.html.twig', [
            'config' => [], 'amazonOrderReferenceId' => 123, ])->willReturn('content');

        $this->renderWalletWidget()->shouldReturn('content');
    }
}
