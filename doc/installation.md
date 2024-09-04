# Installation

## Overview:
GENERAL
- [Requirements](#requirements)
- [Composer](#composer)
- [Basic configuration](#basic-configuration)
---
FRONTEND
- [Templates](#templates)
---
ADDITIONAL
- [Additional configuration](#additional-configuration)
- [Tests](#tests)
- [Known Issues](#known-issues)
---

## Requirements:
We work on stable, supported and up-to-date versions of packages. We recommend you to do the same.

| Package       | Version         |
|---------------|-----------------|
| PHP           | \>8.0           |
| sylius/sylius | 1.12.x - 1.13.x |
| MySQL         | \>= 5.7         |
| NodeJS        | \>= 18.x        |

## Composer:
```bash
composer require bitbag/amazon-pay-plugin
```

## Basic configuration:
Add plugin dependencies to your `config/bundles.php` file:

```php
# config/bundles.php

return [
    ...
    BitBag\SyliusAmazonPayPlugin\BitBagSyliusAmazonPayPlugin::class => ['all' => true],
];
```

Add routing to your `config/routes.yaml` file:
```yaml
# config/routes.yaml

bitbag_sylius_amazon_pay_plugin:
    resource: "@BitBagSyliusAmazonPayPlugin/Resources/config/routing.yml"
    prefix: /
```

Add routing to your `config/routes/sylius_shop.yaml` file:
```yaml
# config/routes/sylius_shop.yaml

sylius_shop_checkout_start:
    path: /{_locale}/checkout-start
    methods: [GET]
    defaults:
        _controller: bitbag_sylius_amazon_pay_plugin.controller.action.checkout_start
    requirements:
        _locale: ^[a-z]{2}(?:_[A-Z]{2})?$  
```

### Change the order of steps in the checkout
Add checkout resolver to `config/_sylius.yml`:

```yaml
sylius_shop:
    checkout_resolver:
       pattern: /checkout/.+
       route_map:
           empty_order:
               route: sylius_shop_cart_summary
           cart:
               route: sylius_shop_checkout_address
           addressed:
               route: sylius_shop_checkout_select_payment
           payment_selected:
               route: sylius_shop_checkout_select_shipping
           payment_skipped:
               route: sylius_shop_checkout_select_shipping
           shipping_selected:
               route: sylius_shop_checkout_complete
           shipping_skipped:
               route: sylius_shop_checkout_complete
```

Add state machine configuration for example to `config/packages/state_machine.yaml`:
```yaml
# config/packages/state_machine.yaml

winzou_state_machine:
    sylius_order_checkout:
       transitions:
           select_payment:
               from: [payment_selected, shipping_skipped, shipping_selected, addressed]
               to: payment_selected
           complete:
               from: [payment_selected, payment_skipped, shipping_selected, shipping_skipped]
               to: completed
```


## Templates
Copy required templates into correct directories in your project.

**ShopBundle** (`templates/bundles/SyliusShopBundle`):
```
vendor/bitbag/amazon-pay-plugin/tests/Application/templates/bundles/SyliusShopBundle/Checkout/SelectPayment/_navigation.html.twig
vendor/bitbag/amazon-pay-plugin/tests/Application/templates/bundles/SyliusShopBundle/Checkout/SelectShipping/_navigation.html.twig
vendor/bitbag/amazon-pay-plugin/tests/Application/templates/bundles/SyliusShopBundle/Checkout/_steps.html.twig
```

### Install assets
```bash
bin/console assets:install 
```
... or if using theme:
```bash
bin/console sylius:theme:assets:install
```

### Run commands
```bash
yarn install
yarn encore dev # or prod, depends on your environment
```

### Clear application cache by using command:
```bash
bin/console cache:clear
```
**Note:** If you are running it on production, add the `-e prod` flag to this command.

## Additional configuration
### Cron job
Cron refreshes the status of AmazonPay

- for example:
```bash
*/5 * * * * bin/console bitbag:amazon-pay:update-payment-state
```

### Fixtures
Example fixture configuration:
```yaml
sylius_fixtures:
    suites:
        default:
            fixtures:
                payment_method:
                    options:
                        custom:
                            amazon_pay:
                                code: "amazon_pay"
                                name: "Amazon pay"
                                channels:
                                    - "US_WEB"
                                enabled: true
                                gatewayFactory: amazonpay
                                gatewayName: Amazon pay
                                gatewayConfig:
                                    payum.http_client: '@bitbag.sylius_amazon_pay_plugin.amazon_pay_api_client'
                                    buttonColor: Gold
                                    buttonSize: Large
                                    buttonType: PwA
                                    buttonLanguage: de-DE
                                    environment: sandbox
                                    merchantId: "test" 
                                    accessKey: "test"
                                    secretKey: "test"
                                    clientId: "test"
                                    region: de
```

## Tests
```bash
composer install
cd tests/Application
yarn install
yarn encore dev
bin/console assets:install web -e test
bin/console doctrine:database:create -e test
bin/console doctrine:schema:create -e test
bin/console server:run 127.0.0.1:8080 -d web -e test
open http://localhost:8080
bin/behat
bin/phpspec run
```

## Known issues
### Translations not displaying correctly
For incorrectly displayed translations, execute the command:
```bash
bin/console cache:clear
```
**Note:** If you are running it on production, add the `-e prod` flag to this command.
