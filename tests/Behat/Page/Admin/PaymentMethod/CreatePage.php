<?php

declare(strict_types=1);

namespace Tests\BitBag\SyliusAmazonPayPlugin\Behat\Page\Admin\PaymentMethod;

use Behat\Mink\Element\NodeElement;
use Sylius\Behat\Page\Admin\Crud\CreatePage as BaseCreatePage;

final class CreatePage extends BaseCreatePage implements CreatePageInterface
{
    public function setMerchantId(string $merchantId): void
    {
        $this->getDocument()->fillField('Merchant Id', $merchantId);
    }

    public function setAccessKey(string $accessKey): void
    {
        $this->getDocument()->fillField('Access Key', $accessKey);
    }

    public function setSecretKey(string $secretKey): void
    {
        $this->getDocument()->fillField('Secret Key', $secretKey);
    }

    public function setClientId(string $clientId): void
    {
        $this->getDocument()->fillField('Client Id', $clientId);
    }

    public function setRegion(string $region): void
    {
        $this->getDocument()->fillField('Region', $region);
    }

    public function chooseButtonColor(string $buttonColor): void
    {
        $this->getDocument()->selectFieldOption('Button Color', $buttonColor);
    }

    public function chooseButtonSize(string $buttonSize): void
    {
        $this->getDocument()->selectFieldOption('Button Size', $buttonSize);
    }

    public function chooseButtonType(string $buttonType): void
    {
        $this->getDocument()->selectFieldOption('Button Type', $buttonType);
    }

    public function chooseButtonLanguage(string $buttonLanguage): void
    {
        $this->getDocument()->selectFieldOption('Button Language', $buttonLanguage);
    }

    public function chooseEnvironment(string $environment): void
    {
        $this->getDocument()->selectFieldOption('Environment', $environment);
    }

    public function containsErrorWithMessage(string $message, bool $strict = true): bool
    {
        $validationMessageElements = $this->getDocument()->findAll('css', '.sylius-validation-error');
        $result = false;

        /** @var NodeElement $validationMessageElement */
        foreach ($validationMessageElements as $validationMessageElement) {
            if (true === $strict && $message === $validationMessageElement->getText()) {
                return true;
            }
            if (false === $strict && strstr($validationMessageElement->getText(), $message)) {
                return true;
            }
        }

        return $result;
    }
}
