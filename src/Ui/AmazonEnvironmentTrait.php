<?php

declare(strict_types=1);

namespace Tierperso\SyliusAmazonPayPlugin\Ui;

trait AmazonEnvironmentTrait
{
    /** @var string */
    private $environment;

    public function getAmazonApiEnvironment(): ?string
    {
        if ('dev' === $this->environment) {
            return '/sandbox';
        }

        return null;
    }
}
