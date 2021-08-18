<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusAmazonPayPlugin\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\NotBlank;

final class AmazonPayGatewayConfigurationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('buttonColor', ChoiceType::class, [
                'choices' => [
                    'bitbag_sylius_amazon_pay_plugin.ui.color_gold' => 'Gold',
                    'bitbag_sylius_amazon_pay_plugin.ui.color_light_gray' => 'LightGray',
                    'bitbag_sylius_amazon_pay_plugin.ui.color_dark_gray' => 'DarkGray',
                ],
                'label' => 'bitbag_sylius_amazon_pay_plugin.ui.button_color',
            ])
            ->add('buttonSize', ChoiceType::class, [
                'choices' => [
                    'bitbag_sylius_amazon_pay_plugin.ui.small' => 'small',
                    'bitbag_sylius_amazon_pay_plugin.ui.medium' => 'medium',
                    'bitbag_sylius_amazon_pay_plugin.ui.large' => 'large',
                    'bitbag_sylius_amazon_pay_plugin.ui.x-large' => 'x-large',
                ],
                'label' => 'bitbag_sylius_amazon_pay_plugin.ui.button_size',
            ])
            ->add('buttonType', ChoiceType::class, [
                'choices' => [
                    'bitbag_sylius_amazon_pay_plugin.ui.login_with_amazon' => 'LwA',
                    'bitbag_sylius_amazon_pay_plugin.ui.amazon_pay' => 'PwA',
                ],
                'label' => 'bitbag_sylius_amazon_pay_plugin.ui.button_type',
            ])
            ->add('buttonLanguage', ChoiceType::class, [
                'choices' => [
                    'bitbag_sylius_amazon_pay_plugin.ui.language_german' => 'de-DE',
                    'bitbag_sylius_amazon_pay_plugin.ui.language_english' => 'en-GB',
                    'bitbag_sylius_amazon_pay_plugin.ui.language_spanish' => 'es-ES',
                    'bitbag_sylius_amazon_pay_plugin.ui.language_french' => 'fr-FR',
                    'bitbag_sylius_amazon_pay_plugin.ui.language_italian' => 'it-IT',
                ],
                'label' => 'bitbag_sylius_amazon_pay_plugin.ui.button_language',
            ])
            ->add('environment', ChoiceType::class, [
                'choices' => [
                    'bitbag_sylius_amazon_pay_plugin.ui.production' => 'production',
                    'bitbag_sylius_amazon_pay_plugin.ui.sandbox' => 'sandbox',
                ],
                'label' => 'bitbag_sylius_amazon_pay_plugin.ui.environment',
            ])
            ->add('merchantId', TextType::class, [
                'label' => 'bitbag_sylius_amazon_pay_plugin.ui.merchant_id',
                'constraints' => [
                    new NotBlank([
                        'message' => 'bitbag_sylius_amazon_pay_plugin.merchant_id.not_blank',
                        'groups' => ['sylius'],
                    ]),
                ],
            ])
            ->add('accessKey', TextType::class, [
                'label' => 'bitbag_sylius_amazon_pay_plugin.ui.access_key',
                'constraints' => [
                    new NotBlank([
                        'message' => 'bitbag_sylius_amazon_pay_plugin.access_key.not_blank',
                        'groups' => ['sylius'],
                    ]),
                ],
            ])
            ->add('secretKey', TextType::class, [
                'label' => 'bitbag_sylius_amazon_pay_plugin.ui.secret_key',
                'constraints' => [
                    new NotBlank([
                        'message' => 'bitbag_sylius_amazon_pay_plugin.secret_key.not_blank',
                        'groups' => ['sylius'],
                    ]),
                ],
            ])
            ->add('clientId', TextType::class, [
                'label' => 'bitbag_sylius_amazon_pay_plugin.ui.client_id',
                'constraints' => [
                    new NotBlank([
                        'message' => 'bitbag_sylius_amazon_pay_plugin.client_id.not_blank',
                        'groups' => ['sylius'],
                    ]),
                ],
            ])
            ->add('region', ChoiceType::class, [
                'label' => 'bitbag_sylius_amazon_pay_plugin.ui.region',
                'choices' => [
                    'de' => 'de',
                    'uk' => 'uk',
                    'us' => 'us',
                    'jp' => 'jp',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'bitbag_sylius_amazon_pay_plugin.region.not_blank',
                        'groups' => ['sylius'],
                    ]),
                ],
            ])
            ->add('type', HiddenType::class, [
                'empty_data' => 'amazonpay'
            ])
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                $data = $event->getData();

                $data['payum.http_client'] = '@bitbag.sylius_amazon_pay_plugin.amazon_pay_api_client';
                $event->setData($data);
            })
        ;
    }
}
