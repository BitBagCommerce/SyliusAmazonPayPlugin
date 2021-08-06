# [![](https://bitbag.io/wp-content/uploads/2021/08/AmazonPay.png)](https://bitbag.io/contact-us/?utm_source=github&utm_medium=referral&utm_campaign=plugins_amazon_pay)

# BitBag SyliusAmazonPayPlugin
----
<p>
 <img src="https://sylius.com/assets/badge-approved-by-sylius.png" width="85">
</p> 
At BitBag we do believe in open source. However, we are able to do it just because of our awesome clients, who are kind enough to share some parts of our work with the community. Therefore, if you feel like there is a possibility for us working together, feel free to reach us out. You will find out more about our professional services, technologies and contact details at:

[https://bitbag.io/](https://bitbag.io/contact-us).

## Table of Content

***

* [Overview](#Overview)
* [Support](#we-are-here-to-help)
* [Installation](#installation)
   * [Cron job](#cron-job)
   * [Fixtures](#Fixtures)
   * [Testing](#testing)
* [About us](#about-us)
   * [Community](#community)
* [Demo Sylius shop](#demo-sylius-shop)
* [Additional Sylius resources for developers](#additional-resources-for-developers)
* [License](#license)
* [Contact](#contact)

# Overview
----
This plugin allows you to integrate AmazonPay payment with Sylius platform app.

## We are here to help
This **open-source plugin was developed to help the Sylius community** and make Mollie payments platform available to any Sylius store. If you have any additional questions, would like help with installing or configuring the plugin or need any assistance with your Sylius project - let us know!

[![](https://bitbag.io/wp-content/uploads/2020/10/button-contact.png)](https://bitbag.io/contact-us/?utm_source=github&utm_medium=referral&utm_campaign=plugins_amazon_pay)


# Installation

1. Require plugin with composer:
   ```bash
    $ composer require bitbag/amazon-pay-plugin
   ```

2. Add plugin class to your bundles.php file:

     ```bash
        $bundles = [
            BitBag\SyliusAmazonPayPlugin\BitBagSyliusAmazonPayPlugin::class => ['all' => true],
        ];

     ```
3. Import routing on top of your config/routes.yaml file:
    ```yaml
    bitbag_sylius_amazon_pay_plugin:
        resource: "@BitBagSyliusAmazonPayPlugin/Resources/config/routing.yml"
        prefix: /
    ```
4. Add routing to sylius_shop.yml:
    ```yaml
    sylius_shop_checkout_start:
       path: /{_locale}/checkout-start
       methods: [GET]
       defaults:
           _controller: bitbag_sylius_amazon_pay_plugin.controller.action.checkout_start
       requirements:
           _locale: ^[a-z]{2}(?:_[A-Z]{2})?$  
   ```
5. Install assets:
   ```bash 
    bin/console assets:install --symlink
   ```
6. Install theme assets (only if using a theme):
   ```bash 
    bin/console sylius:theme:assets:install
   ```
7. Clear cache:
   ```bash 
    bin/console cache:clear
   ```

## Change the order of steps in the checkout
 Add checkout resolver to _sylius.yml:

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

Add state machine in _sylius.yml:

```yaml
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
## Cron job
Cron refreshes the status of AmazonPay

for example:

```bash
*/5 * * * * bin/console bitbag:amazon-pay:update-payment-state
```

## Fixtures
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

## Testing

```bash
$ composer install
$ cd tests/Application
$ yarn install
$ yarn run gulp
$ bin/console assets:install web -e test
$ bin/console doctrine:database:create -e test
$ bin/console doctrine:schema:create -e test
$ bin/console server:run 127.0.0.1:8080 -d web -e test
$ open http://localhost:8080
$ bin/behat
$ bin/phpspec run
```
# About us
---

BitBag is an agency that provides high-quality **eCommerce and Digital Experience software**. Our main area of expertise includes eCommerce consulting and development for B2C, B2B, and Multi-vendor Marketplaces.
The scope of our services related to Sylius includes:
- **Consulting** in the field of strategy development
- Personalized **headless software development**
- **System maintenance and long-term support**
- **Outsourcing**
- **Plugin development**
- **Data migration**

Some numbers regarding Sylius:
* **20+ experts** including consultants, UI/UX designers, Sylius trained front-end and back-end developers,
* **100+ projects** delivered on top of Sylius,
* Clients from  **20+ countries**
* **3+ years** in the Sylius ecosystem.

---

If you need some help with Sylius development, don't be hesitate to contact us directly. You can fill the form on [this site](https://bitbag.io/contact-us/?utm_source=github&utm_medium=referral&utm_campaign=plugins_amazon_pay) or send us an e-mail to hello@bitbag.io!

---

[![](https://bitbag.io/wp-content/uploads/2020/10/badges-sylius.png)](https://bitbag.io/contact-us/?utm_source=github&utm_medium=referral&utm_campaign=plugins_amazon_pay)

## Community
----
For online communication, we invite you to chat with us & other users on [Sylius Slack](https://sylius-devs.slack.com/).

# Demo Sylius shop
---

We created a demo app with some useful use-cases of plugins!
Visit b2b.bitbag.shop to take a look at it. The admin can be accessed under b2b.bitbag.shop/admin/login link and sylius: sylius credentials.
Plugins that we have used in the demo:
| BitBag's Plugin | GitHub | Sylius' Store|
| ------ | ------ | ------|
| ACL PLugin | *Private. Available after the purchasing.*| https://plugins.sylius.com/plugin/access-control-layer-plugin/|
| Braintree Plugin | https://github.com/BitBagCommerce/SyliusBraintreePlugin |https://plugins.sylius.com/plugin/braintree-plugin/|
| CMS Plugin | https://github.com/BitBagCommerce/SyliusCmsPlugin | https://plugins.sylius.com/plugin/cmsplugin/|
| Elasticsearch Plugin | https://github.com/BitBagCommerce/SyliusElasticsearchPlugin | https://plugins.sylius.com/plugin/2004/|
| Mailchimp Plugin | https://github.com/BitBagCommerce/SyliusMailChimpPlugin | https://plugins.sylius.com/plugin/mailchimp/ |
| Multisafepay Plugin | https://github.com/BitBagCommerce/SyliusMultiSafepayPlugin |
| Wishlist Plugin | https://github.com/BitBagCommerce/SyliusWishlistPlugin | https://plugins.sylius.com/plugin/wishlist-plugin/|
| **Sylius' Plugin** | **GitHub** | **Sylius' Store** |
| Admin Order Creation Plugin | https://github.com/Sylius/AdminOrderCreationPlugin | https://plugins.sylius.com/plugin/admin-order-creation-plugin/ |
| Invoicing Plugin | https://github.com/Sylius/InvoicingPlugin | https://plugins.sylius.com/plugin/invoicing-plugin/ |
| Refund Plugin | https://github.com/Sylius/RefundPlugin | https://plugins.sylius.com/plugin/refund-plugin/ |

**If you need an overview of Sylius' capabilities, schedule a consultation with our expert.**

[![](https://bitbag.io/wp-content/uploads/2020/10/button_free_consulatation-1.png)](https://bitbag.io/contact-us/?utm_source=github&utm_medium=referral&utm_campaign=plugins_amazon_pay)

## Additional resources for developers
---
To learn more about our contribution workflow and more, we encourage ypu to use the following resources:
* [Sylius Documentation](https://docs.sylius.com/en/latest/)
* [Sylius Contribution Guide](https://docs.sylius.com/en/latest/contributing/)
* [Sylius Online Course](https://sylius.com/online-course/)

## License
---

This plugin's source code is completely free and released under the terms of the MIT license.

[//]: # (These are reference links used in the body of this note and get stripped out when the markdown processor does its job. There is no need to format nicely because it shouldn't be seen.)

## Contact
---
If you want to contact us, the best way is to fill the form on [our website](https://bitbag.io/contact-us/?utm_source=github&utm_medium=referral&utm_campaign=plugins_amazon_pay) or send us an e-mail to hello@bitbag.io with your question(s). We guarantee that we answer as soon as we can!

[![](https://bitbag.io/wp-content/uploads/2020/10/footer.png)](https://bitbag.io/contact-us/?utm_source=github&utm_medium=referral&utm_campaign=plugins_shipping_subscription)
