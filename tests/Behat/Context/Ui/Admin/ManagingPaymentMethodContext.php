<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace Tests\BitBag\SyliusAmazonPayPlugin\Behat\Context\Ui\Admin;

use Behat\Behat\Context\Context;
use Tests\BitBag\SyliusAmazonPayPlugin\Behat\Page\Admin\PaymentMethod\CreatePageInterface;
use Webmozart\Assert\Assert;

final class ManagingPaymentMethodContext implements Context
{
    /** @var CreatePageInterface */
    private $createPage;

    public function __construct(CreatePageInterface $createPage)
    {
        $this->createPage = $createPage;
    }

    /**
     * @Given I want to create a new AmazonPay payment method
     */
    public function iWantToCreateANewAmazonpayPaymentMethod(): void
    {
        $this->createPage->open(['factory' => 'amazonpay']);
    }

    /**
     * @When I fill the Merchant Id with :merchantId
     */
    public function iFillTheMerchantIdWith(string $merchantId)
    {
        $this->createPage->setMerchantId($merchantId);
    }

    /**
     * @When I fill the Access Key with :accessKey
     */
    public function iFillTheAccessKeyWith(string $accessKey): void
    {
        $this->createPage->setAccessKey($accessKey);
    }

    /**
     * @When I fill the Secret Key with :secretKey
     */
    public function iFillTheSecretKeyWith(string $secretKey): void
    {
        $this->createPage->setSecretKey($secretKey);
    }

    /**
     * @When I fill the Client Id with :clientId
     */
    public function iFillTheClientIdWith(string $clientId): void
    {
        $this->createPage->setClientId($clientId);
    }

    /**
     * @When I fill the Region with :region
     */
    public function iFillTheRegionWith(string $region): void
    {
        $this->createPage->setRegion($region);
    }

    /**
     * @When I select :buttonColor as its buttonColor
     */
    public function iSelectButtonColor(string $buttonColor): void
    {
        $this->createPage->chooseButtonColor($buttonColor);
    }

    /**
     * @When I select :buttonSize as its buttonSize
     */
    public function iSelectButtonSize(string $buttonSize): void
    {
        $this->createPage->chooseButtonSize($buttonSize);
    }

    /**
     * @When I select :buttonType as its buttonType
     */
    public function iSelectButtonType(string $buttonType): void
    {
        $this->createPage->chooseButtonType($buttonType);
    }

    /**
     * @When I select :buttonLanguage as its buttonLanguage
     */
    public function iSelectButtonLanguage(string $buttonLanguage): void
    {
        $this->createPage->chooseButtonLanguage($buttonLanguage);
    }

    /**
     * @When I select :environment as its environment
     */
    public function iSelectEnvironment(string $environment): void
    {
        $this->createPage->chooseEnvironment($environment);
    }

    /**
     * @Then I should be notified that :message
     */
    public function iShouldBeNotifiedThat(string $message): void
    {
        Assert::true($this->createPage->containsErrorWithMessage($message));
    }

    /**
     * @Then I should be notified that :fields fields cannot be blank
     */
    public function iShouldBeNotifiedThatCannotBeBlank(string $fields): void
    {
        $fields = explode(',', $fields);
        foreach ($fields as $field) {
            Assert::true($this->createPage->containsErrorWithMessage(sprintf(
                '%s cannot be blank.',
                trim($field),
            )));
        }
    }
}
