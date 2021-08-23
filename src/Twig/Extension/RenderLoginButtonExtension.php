<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusAmazonPayPlugin\Twig\Extension;

use BitBag\SyliusAmazonPayPlugin\AmazonPayGatewayFactory;
use BitBag\SyliusAmazonPayPlugin\Resolver\PaymentMethodResolverInterface;
use Symfony\Component\Templating\EngineInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

final class RenderLoginButtonExtension extends AbstractExtension
{
    /** @var Environment */
    private $templating;

    /** @var PaymentMethodResolverInterface */
    private $paymentMethodResolver;

    public function __construct(Environment $templating, PaymentMethodResolverInterface $paymentMethodResolver)
    {
        $this->templating = $templating;
        $this->paymentMethodResolver = $paymentMethodResolver;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('bitbag_amazon_pay_render_login_button', [$this, 'renderLoginButton'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function renderLoginButton(): string
    {
        $paymentMethod = $this->paymentMethodResolver->resolvePaymentMethod(AmazonPayGatewayFactory::FACTORY_NAME);

        if (null === $paymentMethod) {
            return '';
        }

        $config = $paymentMethod->getGatewayConfig()->getConfig();

        return $this->templating->render('@BitBagSyliusAmazonPayPlugin/AmazonPay/Login/_button.html.twig', [
            'config' => $config,
        ]);
    }
}
