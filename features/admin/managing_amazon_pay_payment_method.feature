@managing_amazon_pay_payment_method
Feature: Adding a new amazon pay payment method
  In order to pay for orders in different ways
  As an administrator
  I want to add a new payment method to the registry

  Background:
    Given the store operates on a channel named "Web-USD" in "USD" currency
    Given I am logged in as an administrator

  @ui
  Scenario: Adding a new amazon pay payment method
    Given I want to create a new AmazonPay payment method
    When I name it "AmazonPay" in "English (United States)"
    And I specify its code as "amazonpay_test"
    And I fill the Merchant Id with "merchant_id"
    And I fill the Access Key with "access_key"
    And I fill the Secret Key with "secret_key"
    And I fill the Client Id with "client_id"
    And I fill the Region with "de"
    And I select "Gold" as its buttonColor
    And I select "Large" as its buttonSize
    And I select "Amazon Pay" as its buttonType
    And I select "German" as its buttonLanguage
    And I select "Sandbox" as its environment
    And make it available in channel "Web-USD"
    And I add it
    Then I should be notified that it has been successfully created
    And the payment method "AmazonPay" should appear in the registry
