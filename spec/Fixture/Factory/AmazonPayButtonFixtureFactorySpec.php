<?php

declare(strict_types=1);

namespace spec\Tierperso\SyliusAmazonPayPlugin\Fixtures\Factory;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Tierperso\SyliusAmazonPayPlugin\Fixtures\Factory\AmazonPayButtonFixtureFactory;

final class AmazonPayButtonFixtureFactorySpec extends ObjectBehavior
{
    function let(
        FactoryInterface $amazonPayButtonFactory

    ) {
        $this->beConstructedWith($amazonPayButtonFactory);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(AmazonPayButtonFixtureFactory::class);
    }
}
