<?php

declare(strict_types=1);

namespace spec\Tierperso\SyliusAmazonPayPlugin\Twig\Extension;

use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\PaymentMethodInterface;
use Symfony\Component\Templating\EngineInterface;
use Tierperso\SyliusAmazonPayPlugin\Resolver\PaymentMethodResolverInterface;
use Sylius\Component\Order\Context\CartContextInterface;
use Tierperso\SyliusAmazonPayPlugin\Twig\Extension\RenderAddressBookWidgetExtension;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

final class RenderAddressBookWidgetExtensionSpec extends ObjectBehavior
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
        $this->shouldHaveType(RenderAddressBookWidgetExtension::class);
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

    function it_return_empty_string_if_no_payment_method(
        PaymentMethodResolverInterface $paymentMethodResolver): void
    {
        $paymentMethodResolver->resolvePaymentMethod('amazonpay')->willReturn(null);

        $this->renderAddressBookWidget()->shouldReturn('');
    }

    function it_renders_address_book_widget(
        PaymentMethodResolverInterface $paymentMethodResolver,
        PaymentMethodInterface $paymentMethod,
        CartContextInterface $cartContext,
        OrderInterface $order,
        EngineInterface $templatingEngine
    ): void {
        $paymentMethodResolver->resolvePaymentMethod('amazonpay')->willReturn($paymentMethod);

        $paymentMethod->getGatewayConfig()->shouldBeCalled();

        $cartContext->getCart()->willReturn($order);

        $order->getLastPayment()->getDetails()->shouldBeCalled();

        $templatingEngine->render('TierpersoSyliusAmazonPayPlugin:AmazonPay/AddressBook:_widget.html.twig', [
            'config' => [], 'amazonOrderReferenceId' => '123'])->willReturn('content');

        $this->renderAddressBookWidget()->shouldReturn('content');
    }
}
