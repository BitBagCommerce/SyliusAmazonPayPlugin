<?php

declare(strict_types=1);

namespace Tests\BitBag\SyliusAmazonPayPlugin\Behat\Page\Admin\PaymentMethod;

use Behat\Mink\Element\NodeElement;
use Sylius\Behat\Page\Admin\Crud\CreatePageInterface as BaseCreatePageInterface;

interface CreatePageInterface extends BaseCreatePageInterface
{
    /**
     * @param string $merchantId
     */
    public function setMerchantId(string $merchantId): void;

    /**
     * @param string $accessKey
     */
    public function setAccessKey(string $accessKey): void;

    /**
     * @param string $secretKey
     */
    public function setSecretKey(string $secretKey): void;

    /**
     * @param string $clientId
     */
    public function setClientId(string $clientId): void;

    /**
     * @param string $region
     */
    public function setRegion(string $region): void;

    /**
     * @param string $buttonColor
     */
    public function chooseButtonColor(string $buttonColor): void;

    /**
     * @param string $buttonSize
     */
    public function chooseButtonSize(string $buttonSize): void;

    /**
     * @param string $buttonType
     */
    public function chooseButtonType(string $buttonType): void;

    /**
     * @param string $buttonLanguage
     */
    public function chooseButtonLanguage(string $buttonLanguage): void;

    /**
     * @param string $environment
     */
    public function chooseEnvironment(string $environment): void;

    /**
     * @param string $message
     * @param bool $strict
     *
     * @return bool
     */
    public function containsErrorWithMessage(string $message, bool $strict = true): bool;
}
