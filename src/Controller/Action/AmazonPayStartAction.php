<?php

declare(strict_types=1);

namespace BitBag\SyliusAmazonPayPlugin\Controller\Action;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Templating\EngineInterface;

final class AmazonPayStartAction
{
    /** @var EngineInterface */
    private $templatingEngine;

    public function __construct(EngineInterface $templatingEngine)
    {
        $this->templatingEngine = $templatingEngine;
    }

    public function __invoke(Request $request): Response
    {
        return new Response(
            $this->templatingEngine->render('BitBagSyliusAmazonPayPlugin:AmazonPay:amazonPayStart.html.twig')
        );
    }
}
