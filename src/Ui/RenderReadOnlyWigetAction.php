<?php

declare(strict_types=1);

namespace Tierperso\SyliusAmazonPayPlugin\Ui;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

final class RenderReadOnlyWigetAction
{
    use AmazonEnvironmentTrait;

    /** @var EngineInterface */
    private $templatingEngine;

    public function __construct(EngineInterface $templatingEngine)
    {
        $this->templatingEngine = $templatingEngine;
    }

    public function __invoke(Request $request): Response
    {
        $amazonOrderReferenceId = $request->getSession()->get('amazon_order_reference_id');

        return $this->templatingEngine->renderResponse(
            'TierpersoSyliusAmazonPayPlugin:AmazonPay:read_only_widget.html.twig',
            [
                'amazon' => [
                    'order_reference_id' => $amazonOrderReferenceId,
                    'merchantId' => 'A5445N2KWSM0Z'

                ],
            ]
        );
    }

}
