<?php

declare(strict_types=1);

namespace Tierperso\SyliusAmazonPayPlugin\Ui;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Response;

final class RenderAmazonPayButtonWidgetAction
{
    /** @var ParameterBagInterface */
    private $environment;

    /** @var EngineInterface */
    private $templatingEngine;

    public function __construct(EngineInterface $templatingEngine, ParameterBagInterface $environment)
    {
        $this->templatingEngine = $templatingEngine;
        $this->environment = $environment;
    }

    public function __invoke(): Response
    {
        // TODO fetch data of AmazonPay Payment Gateway

        if ($this->environment->get('kernel.environment') === "dev" ) {
            $api_env = '/sandbox';
        }
        elseif ($this->environment->get('kernel.environment') === "prod") {
            $api_env = '';
        }

        return $this->templatingEngine->renderResponse(
            'TierpersoSyliusAmazonPayPlugin:AmazonPay:buttonWidget.html.twig',
            [
                'amazon' => [
                    'api_env' => $api_env,
                    'clientId' => 'amzn1.application-oa2-client.46d27733e6044b08860955d63511ad19',
                    'merchantId' => 'A5445N2KWSM0Z',
                ],
            ]
        );
    }
}
