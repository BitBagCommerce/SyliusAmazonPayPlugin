@managing_amazon_pay_payment_method
Feature: AmazonPay payment method validation
  In order to avoid making mistakes when managing a payment method
  As an administrator
  I want to be prevented from adding it without specifying required fields

  Background:
    Given the store operates on a channel named "Web-RUB" in "RUB" currency
    And the store has a payment method "Offline" with a code "offline"
    And I am logged in as an administrator

  @ui
  Scenario: Trying to add a new amazon pay payment method without specifying required configuration
    Given I want to create a new AmazonPay payment method
    When I name it "AmazonPay" in "English (United States)"
    And I add it
    Then I should be notified that "Merchant Id" fields cannot be blank
    Then I should be notified that "Access Key" fields cannot be blank
    Then I should be notified that "Secret Key" fields cannot be blank
    Then I should be notified that "Client Id" fields cannot be blank
