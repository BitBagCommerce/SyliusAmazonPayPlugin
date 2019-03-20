<?php

declare(strict_types=1);

namespace Tierperso\SyliusAmazonPayPlugin\Fixtures\Factory;

use Sylius\Bundle\CoreBundle\Fixture\Factory\AbstractExampleFactory;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Tierperso\SyliusAmazonPayPlugin\Entity\AmazonPayButtonInterface;

final class AmazonPayButtonFixtureFactory extends AbstractExampleFactory
{
    /**
     * @var FactoryInterface
     */
    private $amazonPayButtonFactory;

    /**
     * @var OptionsResolver
     */
    private $optionsResolver;

    public function __construct(FactoryInterface $amazonPayButtonFactory)
    {
        $this->amazonPayButtonFactory = $amazonPayButtonFactory;

        $this->optionsResolver = new OptionsResolver();

        $this->configureOptions($this->optionsResolver);
    }

    protected function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefault('button_type', function (Options $options): string {
                return 'PwA';
            })
            ->setDefault('button_color', function (Options $options): string {
                return 'Gold';
            })
            ->setDefault('button_size', function (Options $options): string {
                return 'large';
            })
            ->setDefault('button_language', function (Options $options): string {
                return 'de-DE';
            })

        ;
    }

    public function create(array $options = []): AmazonPayButtonInterface
    {
        $options = $this->optionsResolver->resolve($options);

        /** @var AmazonPayButtonInterface $amazonPayButton */
        $amazonPayButton = $this->amazonPayButtonFactory->createNew();
        $amazonPayButton->setButtonType($options['button_type']);
        $amazonPayButton->setButtonColor($options['button_color']);
        $amazonPayButton->setButtonSize($options['button_size']);
        $amazonPayButton->setButtonLanguage($options['button_language']);

        return $amazonPayButton;
    }
}
