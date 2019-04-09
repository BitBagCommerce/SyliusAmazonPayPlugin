<?php

namespace spec\Tierperso\SyliusAmazonPayPlugin\Controller\Action;

use Tierperso\SyliusAmazonPayPlugin\Controller\Action\AmazonPayStartAction;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Templating\EngineInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class AmazonPayStartActionSpec extends ObjectBehavior
{
    function let(
        EngineInterface $templatingEngine): void {
        $this->beConstructedWith($templatingEngine);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(AmazonPayStartAction::class);
    }

    function it_start(Request $request, EngineInterface $templatingEngine): void
    {
        $templatingEngine->render('TierpersoSyliusAmazonPayPlugin:AmazonPay:amazonPayStart.html.twig')->shouldBeCalled();

        $this->__invoke($request);
    }
}
