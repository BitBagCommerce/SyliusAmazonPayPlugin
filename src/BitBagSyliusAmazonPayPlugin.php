<?php

declare(strict_types=1);

namespace BitBag\SyliusAmazonPayPlugin;

use Sylius\Bundle\CoreBundle\Application\SyliusPluginTrait;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class BitBagSyliusAmazonPayPlugin extends Bundle
{
    use SyliusPluginTrait;
}
