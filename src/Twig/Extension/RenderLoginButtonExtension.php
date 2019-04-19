<?php

declare(strict_types=1);

namespace BitBag\SyliusAmazonPayPlugin\Twig\Extension;

use BitBag\SyliusAmazonPayPlugin\AmazonPayGatewayFactory;
use BitBag\SyliusAmazonPayPlugin\Resolver\PaymentMethodResolverInterface;
use Symfony\Component\Templating\EngineInterface;
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
            new TwigFunction('bitbag_amazon_pay_render_login_button', [$this, 'renderLoginButton'], ['is_safe' => ['html']]),
        ];
    }

    public function renderLoginButton(): string
    {
        $paymentMethod = $this->paymentMethodResolver->resolvePaymentMethod(AmazonPayGatewayFactory::FACTORY_NAME);

        if (null === $paymentMethod) {
            return '';
        }

        $config = $paymentMethod->getGatewayConfig()->getConfig();

        return $this->templatingEngine->render('BitBagSyliusAmazonPayPlugin:AmazonPay/Login:_button.html.twig', [
            'config' => $config,
        ]);
    }
}
