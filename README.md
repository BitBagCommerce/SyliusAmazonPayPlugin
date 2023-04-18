# [![](https://bitbag.io/wp-content/uploads/2021/08/AmazonPay.png)](https://bitbag.io/contact-us/?utm_source=github&utm_medium=referral&utm_campaign=plugins_amazon_pay)

# BitBag SyliusAmazonPayPlugin
----

<p>
 <img align="left" src="https://sylius.com/assets/badge-approved-by-sylius.png" width="85">
</p> 

At BitBag we do believe in open source. However, we are able to do it just because of our awesome clients, who are kind enough to share some parts of our work with the community. Therefore, if you feel like there is a possibility for us to work  together, feel free to reach out. You will find out more about our professional services, technologies, and contact details at [https://bitbag.io/](https://bitbag.io/contact-us/?utm_source=github&utm_medium=referral&utm_campaign=plugins_amazon_pay).

Like what we do? Want to join us? Check out our job listings on our [career page](https://bitbag.io/career/?utm_source=github&utm_medium=referral&utm_campaign=career). Not familiar with Symfony & Sylius yet, but still want to start with us? Join our [academy](https://bitbag.io/pl/akademia?utm_source=github&utm_medium=url&utm_campaign=akademia)!

## Table of Content

***

* [Overview](#overview)
* [Support](#we-are-here-to-help)
* [Installation](#installation)
   * [Cron job](#cron-job)
   * [Fixtures](#Fixtures)
   * [Testing](#testing)
* [About us](#about-us)
   * [Community](#community)
* [Demo](#demo-sylius-shop)
* [License](#license)
* [Contact](#contact)

# Overview
----
This plugin allows you to integrate AmazonPay payment with Sylius platform app.

## We are here to help
This **open-source plugin was developed to help the Sylius community**. If you have any additional questions, would like help with installing or configuring the plugin, or need any assistance with your Sylius project - let us know!

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
5. Copy Sylius templates overridden by plug-in to your templates directory (`templates/bundles/`):

```
mkdir -p templates/bundles/SyliusAdminBundle/
mkdir -p templates/bundles/SyliusShopBundle/

cp -R vendor/bitbag/amazon-pay-plugin/tests/Application/templates/bundles/SyliusAdminBundle/* templates/bundles/SyliusAdminBundle/
cp -R vendor/bitbag/amazon-pay-plugin/tests/Application/templates/bundles/SyliusShopBundle/* templates/bundles/SyliusShopBundle/
```
6. Install assets:
   ```bash 
    bin/console assets:install 
   ```
7. Install theme assets (only if using a theme):
   ```bash 
    bin/console sylius:theme:assets:install
   ```
8. Clear cache:
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
Configure config/packages/webpack_encore.yaml
```yaml
    builds:
        *: *
        shop: '%kernel.project_dir%/public/build/shop'
        admin: '%kernel.project_dir%/public/build/admin'
```
## Testing


Copy Sylius templates overridden by plug-in to your templates directory (`templates/bundles/`):

```bash
    mkdir -p templates/bundles/SyliusAdminBundle/
    mkdir -p templates/bundles/SyliusShopBundle/
    
    cp -R vendor/bitbag/amazon-pay-plugin/tests/Application/templates/bundles/SyliusAdminBundle/* templates/bundles/SyliusAdminBundle/
    cp -R vendor/bitbag/amazon-pay-plugin/tests/Application/templates/bundles/SyliusShopBundle/* templates/bundles/SyliusShopBundle/
```
```bash
$ composer install
$ cd tests/Application
$ yarn install
$ yarn encore dev
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

BitBag is a company of people who **love what they do** and do it right. We fulfill the eCommerce technology stack with **Sylius**, Shopware, Akeneo, and Pimcore for PIM, eZ Platform for CMS, and VueStorefront for PWA. Our goal is to provide real digital transformation with an agile solution that scales with the **clients’ needs**. Our main area of expertise includes eCommerce consulting and development for B2C, B2B, and Multi-vendor Marketplaces.</br>
We are advisers in the first place. We start each project with a diagnosis of problems, and an analysis of the needs and **goals** that the client wants to achieve.</br>
We build **unforgettable**, consistent digital customer journeys on top of the **best technologies**. Based on a detailed analysis of the goals and needs of a given organization, we create dedicated systems and applications that let businesses grow.<br>
Our team is fluent in **Polish, English, German and, French**. That is why our cooperation with clients from all over the world is smooth.

**Some numbers from BitBag regarding Sylius:**
- 50+ **experts** including consultants, UI/UX designers, Sylius trained front-end and back-end developers,
- 120+ projects **delivered** on top of Sylius,
- 25+ **countries** of BitBag’s customers,
- 4+ **years** in the Sylius ecosystem.

**Our services:**
- Business audit/Consulting in the field of **strategy** development,
- Data/shop **migration**,
- Headless **eCommerce**,
- Personalized **software** development,
- **Project** maintenance and long term support,
- Technical **support**.

**Key clients:** Mollie, Guave, P24, Folkstar, i-LUNCH, Elvi Project, WestCoast Gifts.

---

If you need some help with Sylius development, don't be hesitated to contact us directly. You can fill the form on [this site](https://bitbag.io/contact-us/?utm_source=github&utm_medium=referral&utm_campaign=plugins_amazon_pay) or send us an e-mail at hello@bitbag.io!

---

[![](https://bitbag.io/wp-content/uploads/2021/08/sylius-badges-transparent-wide.png)](https://bitbag.io/contact-us/?utm_source=github&utm_medium=referral&utm_campaign=plugins_amazon_pay)

## Community

---- 

For online communication, we invite you to chat with us & other users on [Sylius Slack](https://sylius-devs.slack.com/).

# Demo Sylius Shop

---

We created a demo app with some useful use-cases of plugins!
Visit [sylius-demo.bitbag.io](https://sylius-demo.bitbag.io/) to take a look at it. The admin can be accessed under
[sylius-demo.bitbag.io/admin/login](https://sylius-demo.bitbag.io/admin/login) link and `bitbag: bitbag` credentials.
Plugins that we have used in the demo:

| BitBag's Plugin | GitHub | Sylius' Store|
| ------ | ------ | ------|
| ACL Plugin | *Private. Available after the purchasing.*| https://plugins.sylius.com/plugin/access-control-layer-plugin/|
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
To learn more about our contribution workflow and more, we encourage you to use the following resources:
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

[![](https://bitbag.io/wp-content/uploads/2021/08/badges-bitbag.png)](https://bitbag.io/contact-us/?utm_source=github&utm_medium=referral&utm_campaign=plugins_amazon_pay)
