<?php

declare(strict_types=1);

namespace spec\BitBag\SyliusAmazonPayPlugin\Twig\Extension;

use Sylius\Component\Order\Context\CartContextInterface;
use Symfony\Component\Templating\EngineInterface;
use BitBag\SyliusAmazonPayPlugin\Resolver\PaymentMethodResolverInterface;
use BitBag\SyliusAmazonPayPlugin\Twig\Extension\RenderWalletWidgetExtension;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

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
}
