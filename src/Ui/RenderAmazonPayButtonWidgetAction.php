<?php

declare(strict_types=1);

namespace Tierperso\SyliusAmazonPayPlugin\Ui;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Response;

final class RenderAmazonPayButtonWidgetAction
{
    /** @var EngineInterface */
    private $templatingEngine;

    public function __construct(EngineInterface $templatingEngine)
    {
        $this->templatingEngine = $templatingEngine;
    }

    public function __invoke(): Response
    {
        // TODO fetch data of AmazonPay Payment Gateway

        return $this->templatingEngine->renderResponse(
            'TierpersoSyliusAmazonPayPlugin:AmazonPay:buttonWidget.html.twig',
            [
                'amazon' => [
                    'clientId' => 'amzn1.application-oa2-client.46d27733e6044b08860955d63511ad19',
                    'merchantId' => 'A5445N2KWSM0Z',
                ],
            ]
        );
    }
}
