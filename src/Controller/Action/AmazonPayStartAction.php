<?php

declare(strict_types=1);

namespace BitBag\SyliusAmazonPayPlugin\Controller\Action;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

final class AmazonPayStartAction
{
    /** @var Environment */
    private $templating;

    public function __construct(Environment $templating)
    {
        $this->templating = $templating;
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function __invoke(Request $request): Response
    {
        return new Response(
            $this->templating->render('BitBagSyliusAmazonPayPlugin:AmazonPay:amazonPayStart.html.twig')
        );
    }
}
