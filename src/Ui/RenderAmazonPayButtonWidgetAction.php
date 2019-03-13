<?php

declare(strict_types=1);

namespace Tierperso\SyliusAmazonPayPlugin\Ui;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Response;

final class RenderAmazonPayButtonWidgetAction
{
    /** @var EngineInterface */
    private $templatingEngine;

    /** @var string */
    private $environment;

    public function __construct(EngineInterface $templatingEngine, string $environment)
    {
        $this->templatingEngine = $templatingEngine;
        $this->environment = $environment;
    }

    public function __invoke(): Response
    {
        // TODO fetch data of AmazonPay Payment Gateway
        return $this->templatingEngine->renderResponse(
            'TierpersoSyliusAmazonPayPlugin:AmazonPay:buttonWidget.html.twig',
            [
                'amazon' => [
                    'amazonApiEnvironment' => $this->getAmazonApiEnvironment(),
                    'clientId' => 'amzn1.application-oa2-client.46d27733e6044b08860955d63511ad19',
                    'merchantId' => 'A5445N2KWSM0Z',
                ],
            ]
        );
    }

    private function getAmazonApiEnvironment(): ?string
    {
        if ('dev' === $this->environment) {
            return '/sandbox';
        }

        return null;
    }
}
