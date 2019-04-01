<?php

declare(strict_types=1);

namespace Tierperso\SyliusAmazonPayPlugin\Twig\Extension;

use Symfony\Component\Templating\EngineInterface;
use Tierperso\SyliusAmazonPayPlugin\AmazonPayGatewayFactory;
use Tierperso\SyliusAmazonPayPlugin\Resolver\PaymentMethodResolverInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class RenderLoginButtonExtension extends AbstractExtension
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
            new TwigFunction('tierperso_amazon_pay_render_login_button', [$this, 'renderLoginButton'], ['is_safe' => ['html']]),
        ];
    }

    public function renderLoginButton(): string
    {
        $paymentMethod = $this->paymentMethodResolver->resolvePaymentMethod(AmazonPayGatewayFactory::FACTORY_NAME);

        if (null === $paymentMethod) {
            return '';
        }

        $config = $paymentMethod->getGatewayConfig()->getConfig();

        return $this->templatingEngine->render('TierpersoSyliusAmazonPayPlugin:AmazonPay/Login:_button.html.twig', [
            'config' => $config,
        ]);
    }
}
