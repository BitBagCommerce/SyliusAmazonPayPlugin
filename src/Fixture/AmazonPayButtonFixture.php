<?php

declare(strict_types=1);

namespace AppBundle\Fixtures;

use Tierperso\SyliusAmazonPayPlugin\Entity\AmazonPayButtonInterface;
use Tierperso\SyliusAmazonPayPlugin\Fixtures\Factory\AmazonPayButtonFixtureFactory;
use Doctrine\Common\Persistence\ObjectManager;
use Sylius\Bundle\FixturesBundle\Fixture\AbstractFixture;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

class AmazonPayButtonFixture extends AbstractFixture
{
    /**
     * @var ObjectManager
     */
    private $amazonPayButtonManager;

    /**
     * @var AmazonPayButtonFixtureFactory
     */
    private $amazonPayButtonFactory;

    public function __construct(ObjectManager $amazonPayButtonManager, AmazonPayButtonFixtureFactory $amazonPayButtonFactory)
    {
        $this->amazonPayButtonManager = $amazonPayButtonManager;
        $this->amazonPayButtonFactory = $amazonPayButtonFactory;
    }

    public function load(array $options): void
    {
            /** @var AmazonPayButtonInterface $amazonPayButton */
            $amazonPayButton = $this->amazonPayButtonFactory->create();

            $this->amazonPayButtonManager->persist($amazonPayButton);

            $this->amazonPayButtonManager->flush();
    }

    public function getName(): string
    {
        return 'amazon_pay_button';
    }

    protected function configureOptionsNode(ArrayNodeDefinition $optionsNode): void
    {
        $optionsNode
            ->children()
            ->scalarNode('button_type')->cannotBeEmpty()->end()
            ->scalarNode('button_color')->cannotBeEmpty()->end()
            ->scalarNode('button_size')->cannotBeEmpty()->end()
            ->scalarNode('button_language')->cannotBeEmpty()->end()
        ;
    }
}
