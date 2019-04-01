<?php

declare(strict_types=1);

namespace Tierperso\SyliusAmazonPayPlugin\Twig\Extension;

use Tierperso\SyliusAmazonPayPlugin\AmazonPayGatewayFactory;
use Tierperso\SyliusAmazonPayPlugin\Resolver\PaymentMethodResolverInterface;
use Twig\Extension\AbstractExtension;
use Symfony\Component\Templating\EngineInterface;
use Twig\TwigFunction;

final class RenderAddressBookWidgetExtension extends AbstractExtension
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
            new TwigFunction('tierperso_amazon_pay_render_address_book_widget', [$this, 'renderAddressBookWidget'], ['is_safe' => ['html']]),
        ];
    }

    public function renderAddressBookWidget(): string
    {
        $paymentMethod = $this->paymentMethodResolver->resolvePaymentMethod(AmazonPayGatewayFactory::FACTORY_NAME);

        if (null === $paymentMethod) {
            return '';
        }

        $config = $paymentMethod->getGatewayConfig()->getConfig();

        return $this->templatingEngine->render('TierpersoSyliusAmazonPayPlugin:AmazonPay/AddressBook:_widget.html.twig', [
            'config' => $config,
        ]);
    }
}
