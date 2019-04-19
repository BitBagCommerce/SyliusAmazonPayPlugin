<?php

declare(strict_types=1);

namespace Tests\BitBag\SyliusAmazonPayPlugin\Behat\Page\Admin\PaymentMethod;

use Sylius\Behat\Page\Admin\Crud\CreatePageInterface as BaseCreatePageInterface;

interface CreatePageInterface extends BaseCreatePageInterface
{
    public function setMerchantId(string $merchantId): void;

    public function setAccessKey(string $accessKey): void;

    public function setSecretKey(string $secretKey): void;

    public function setClientId(string $clientId): void;

    public function setRegion(string $region): void;

    public function chooseButtonColor(string $buttonColor): void;

    public function chooseButtonSize(string $buttonSize): void;

    public function chooseButtonType(string $buttonType): void;

    public function chooseButtonLanguage(string $buttonLanguage): void;

    public function chooseEnvironment(string $environment): void;

    public function containsErrorWithMessage(string $message, bool $strict = true): bool;
}
