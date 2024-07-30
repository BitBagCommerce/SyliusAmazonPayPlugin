<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusAmazonPayPlugin\Twig\Extension;

use BitBag\SyliusAmazonPayPlugin\AmazonPayGatewayFactory;
use BitBag\SyliusAmazonPayPlugin\Resolver\PaymentMethodResolverInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

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
