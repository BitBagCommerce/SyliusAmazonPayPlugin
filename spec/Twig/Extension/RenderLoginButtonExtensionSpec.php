<?php

declare(strict_types=1);

namespace spec\BitBag\SyliusAmazonPayPlugin\Twig\Extension;

use BitBag\SyliusAmazonPayPlugin\Resolver\PaymentMethodResolverInterface;
use BitBag\SyliusAmazonPayPlugin\Twig\Extension\RenderLoginButtonExtension;
use Payum\Core\Model\GatewayConfigInterface;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Core\Model\PaymentMethodInterface;
use Symfony\Component\Templating\EngineInterface;

final class RenderLoginButtonExtensionSpec extends ObjectBehavior
{
    function let(
        EngineInterface $templatingEngine,
        PaymentMethodResolverInterface $paymentMethodResolver
    ): void {
        $this->beConstructedWith($templatingEngine, $paymentMethodResolver);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(RenderLoginButtonExtension::class);
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

        $this->renderLoginButton()->shouldReturn('');
    }

    function it_renders_login_buttont(
        PaymentMethodResolverInterface $paymentMethodResolver,
        PaymentMethodInterface $paymentMethod,
        EngineInterface $templatingEngine,
        GatewayConfigInterface $gatewayConfig
    ): void {
        $gatewayConfig->getConfig()->willReturn([]);
        $paymentMethod->getGatewayConfig()->willReturn($gatewayConfig);
        $paymentMethodResolver->resolvePaymentMethod('amazonpay')->willReturn($paymentMethod);

        $templatingEngine->render('BitBagSyliusAmazonPayPlugin:AmazonPay/Login:_button.html.twig', [
            'config' => [], ])->willReturn('content');

        $this->renderLoginButton()->shouldReturn('content');
    }
}
