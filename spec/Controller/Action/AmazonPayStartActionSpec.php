<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace spec\BitBag\SyliusAmazonPayPlugin\Controller\Action;

use BitBag\SyliusAmazonPayPlugin\Controller\Action\AmazonPayStartAction;
use PhpSpec\ObjectBehavior;
use Symfony\Component\HttpFoundation\Request;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

final class AmazonPayStartActionSpec extends ObjectBehavior
{
    function let(
        Environment $templating): void
    {
        $this->beConstructedWith($templating);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(AmazonPayStartAction::class);
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    function it_start(Request $request, Environment $templating): void
    {
        $templating->render('BitBagSyliusAmazonPayPlugin:AmazonPay:amazonPayStart.html.twig')->shouldBeCalled();

        $this->__invoke($request);
    }
}
