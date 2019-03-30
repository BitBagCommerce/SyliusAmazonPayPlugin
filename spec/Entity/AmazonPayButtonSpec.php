<?php

declare(strict_types=1);

namespace spec\Tierperso\SyliusAmazonPayPlugin\Entity;

use Tierperso\SyliusAmazonPayPlugin\Entity\AmazonPayButton;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sylius\Component\Resource\Model\ResourceInterface;
use Tierperso\SyliusAmazonPayPlugin\Entity\AmazonPayButtonInterface;

final class AmazonPayButtonSpec extends ObjectBehavior
{
    function let(): void
    {
        $this->beConstructedWith('1','PwA','Gold','large','de-DE');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(AmazonPayButton::class);
    }

    function it_implements_an_amazon_pay_button_interface(): void
    {
        $this->shouldImplement(AmazonPayButtonInterface::class);
    }

    function it_has_id(): void
    {
        $this->getId()->shouldReturn('1');
    }

    function it_has_button_type(): void
    {
        $this->getButtonType()->shouldReturn('PwA');
    }

    function it_has_button_color(): void
    {
        $this->getButtonColor()->shouldReturn('Gold');
    }

    function it_has_button_size(): void
    {
        $this->getButtonSize()->shouldReturn('large');
    }

    function it_has_button_button_language(): void
    {
        $this->getButtonLanguage()->shouldReturn('de-DE');
    }

    function its_button_type_is_mutable(): void
    {
        $this->setButtonType('PwA');
        $this->getButtonType()->shouldReturn('PwA');
    }

    function its_button_color_is_mutable(): void
    {
        $this->setButtonColor('Gold');
        $this->getButtonColor()->shouldReturn('Gold');
    }

    function its_button_size_is_mutable(): void
    {
        $this->setButtonSize('large');
        $this->getButtonSize()->shouldReturn('large');
    }

    function its_button_language_is_mutable(): void
    {
        $this->setButtonLanguage('de-DE');
        $this->getButtonLanguage()->shouldReturn('de-DE');
    }
}
