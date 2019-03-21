<?php

declare(strict_types=1);

namespace Tierperso\SyliusAmazonPayPlugin\Ui;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Response;

final class RenderAmazonPayButtonWidgetAction
{
    use AmazonEnvironmentTrait;

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
                    'amazonApiRegion' => '/eur',
                    'clientId' => 'amzn1.application-oa2-client.bce33695af0245ceb924af2ede4b9877',
                    'merchantId' => 'A5445N2KWSM0Z',
                ],
            ]
        );
    }
}
