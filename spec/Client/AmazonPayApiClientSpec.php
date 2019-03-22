<?php

declare(strict_types=1);

namespace spec\Tierperso\SyliusAmazonPayPlugin\Client;

use Tierperso\SyliusAmazonPayPlugin\Client\AmazonPayApiClient;
use AmazonPay\ClientInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

final class AmazonPayApiClientSpec extends ObjectBehavior
{
    function let(
        ClientInterface $client

    ) {
        $this->beConstructedWith($client);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(AmazonPayApiClient::class);
    }

}
