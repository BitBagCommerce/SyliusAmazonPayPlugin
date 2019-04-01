<?php

declare(strict_types=1);

namespace Tierperso\SyliusAmazonPayPlugin\Twig\Extension;

use Tierperso\SyliusAmazonPayPlugin\AmazonPayGatewayFactory;
use Twig\Extension\AbstractExtension;
use Symfony\Component\Templating\EngineInterface;
use Tierperso\SyliusAmazonPayPlugin\Resolver\PaymentMethodResolverInterface;
use Twig\TwigFunction;

final class RenderWalletWidgetExtension extends AbstractExtension
{
    /** @var EngineInterface */
    private $templatingEngine;

    /** @var PaymentMethodResolverInterface */
    private $paymentMethodResolver;

    public function __construct(EngineInterface $templatingEngine, PaymentMethodResolverInterface $paymentMethodResolver)
    {
        $this->templatingEngine = $templatingEngine;
        $this->paymentMethodResolver = $paymentMethodResolver;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('tierperso_amazon_pay_render_wallet_widget', [$this, 'renderWalletWidget'], ['is_safe' => ['html']]),
        ];
    }

    public function renderWalletWidget(): string
    {
        $paymentMethod = $this->paymentMethodResolver->resolvePaymentMethod(AmazonPayGatewayFactory::FACTORY_NAME);

        if (null === $paymentMethod) {
            return '';
        }

        $config = $paymentMethod->getGatewayConfig()->getConfig();

        return $this->templatingEngine->render('TierpersoSyliusAmazonPayPlugin:AmazonPay/Wallet:_widget.html.twig', [
            'config' => $config,
        ]);
    }
}
