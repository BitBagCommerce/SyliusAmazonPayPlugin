<?php

declare(strict_types=1);

namespace Tierperso\SyliusAmazonPayPlugin\Twig\Extension;

use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;

final class RenderAddressBookWidgetExtension extends \Twig_Extension
{
    /** @var EngineInterface */
    private $templatingEngine;

    public function __construct(
        EngineInterface $templatingEngine
    ) {
        $this->templatingEngine = $templatingEngine;
    }
    public function getFunctions(): array
    {

    }

    public function renderAddressBookWidget()
    {

    }
}
