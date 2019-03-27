<?php

declare(strict_types=1);

namespace Tests\Tierperso\SyliusAmazonPayPlugin\Behat\Context\Ui\Admin;

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use Tests\Tierperso\SyliusAmazonPayPlugin\Behat\Page\Admin\PaymentMethod\CreatePageInterface;
use Webmozart\Assert\Assert;

final class ManagingPaymentMethodContext implements Context
{
    /**
     * @var CreatePageInterface
     */
    private $createPage;

    /**
     * @param CreatePageInterface $createPage
     */
    public function __construct(CreatePageInterface $createPage)
    {
        $this->createPage = $createPage;
    }

    /**
     * @Given I want to create a new AmazonPay payment method
     */
    public function iWantToCreateANewAmazonpayPaymentMethod()
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
    public function iFillTheAccessKeyWith($accessKey)
    {
        $this->createPage->setAccessKey($accessKey);
    }

    /**
     * @When I fill the Secret Key with :secretKey
     */
    public function iFillTheSecretKeyWith($secretKey)
    {
        $this->createPage->setSecretKey($secretKey);
    }

    /**
     * @When I fill the Client Id with :clientId
     */
    public function iFillTheClientIdWith($clientId)
    {
        $this->createPage->setClientId($clientId);
    }

    /**
     * @When I fill the Region with :region
     */
    public function iFillTheRegionWith($region)
    {
        $this->createPage->setRegion($region);
    }

    /**
     * @When I select :buttonColor as its buttonColor
     */
    public function iSelectButtonColor($buttonColor)
    {
        $this->createPage->chooseButtonColor($buttonColor);
    }

    /**
     * @When I select :buttonSize as its buttonSize
     */
    public function iSelectButtonSize($buttonSize)
    {
        $this->createPage->chooseButtonSize($buttonSize);
    }

    /**
     * @When I select :buttonType as its buttonType
     */
    public function iSelectButtonType($buttonType)
    {
        $this->createPage->chooseButtonType($buttonType);
    }

    /**
     * @When I select :buttonLanguage as its buttonLanguage
     */
    public function iSelectButtonLanguage($buttonLanguage)
    {
        $this->createPage->chooseButtonLanguage($buttonLanguage);
    }

    /**
     * @When I select :environment as its environment
     */
    public function iSelectEnvironment($environment)
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

}
