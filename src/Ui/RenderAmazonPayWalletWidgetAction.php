<?php

declare(strict_types=1);

namespace Tierperso\SyliusAmazonPayPlugin\Ui;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Response;

final class RenderAmazonPayWalletWidgetAction
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
        return $this->templatingEngine->renderResponse(
            'TierpersoSyliusAmazonPayPlugin:AmazonPay:walletWidget.html.twig',
            [
                'amazon' => [
                    'amazonApiEnvironment' => $this->getAmazonApiEnvironment(),
                    'amazonApiRegion' => '/eur',
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
