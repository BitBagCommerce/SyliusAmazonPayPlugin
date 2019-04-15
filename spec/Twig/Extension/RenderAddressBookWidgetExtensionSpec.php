<?php

namespace spec\BitBag\SyliusAmazonPayPlugin\Twig\Extension;

use Symfony\Component\Templating\EngineInterface;
use BitBag\SyliusAmazonPayPlugin\Twig\Extension\RenderAddressBookWidgetExtension;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RenderAddressBookWidgetExtensionSpec extends ObjectBehavior
{
    function let(
        EngineInterface $templatingEngine
    ): void {
        $this->beConstructedWith($templatingEngine);
    }

    function it_is_initializable()
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
}
