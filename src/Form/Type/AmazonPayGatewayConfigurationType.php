<?php

declare(strict_types=1);

namespace Tierperso\SyliusAmazonPayPlugin\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
                    'tierperso_sylius_amazon_pay_plugin.ui.color_gold' => 'Gold',
                    'tierperso_sylius_amazon_pay_plugin.ui.color_light_gray' => 'LightGray',
                    'tierperso_sylius_amazon_pay_plugin.ui.color_dark_gray' => 'DarkGray',
                ],
                'label' => 'tierperso_sylius_amazon_pay_plugin.ui.button_color',
            ])
            ->add('buttonSize', ChoiceType::class, [
                'choices' => [
                    'tierperso_sylius_amazon_pay_plugin.ui.small' => 'small',
                    'tierperso_sylius_amazon_pay_plugin.ui.medium' => 'medium',
                    'tierperso_sylius_amazon_pay_plugin.ui.large' => 'large',
                    'tierperso_sylius_amazon_pay_plugin.ui.x-large' => 'x-large',
                ],
                'label' => 'tierperso_sylius_amazon_pay_plugin.ui.button_size',
            ])
            ->add('buttonType', ChoiceType::class, [
                'choices' => [
                    'tierperso_sylius_amazon_pay_plugin.ui.login_with_amazon' => 'LwA',
                    'tierperso_sylius_amazon_pay_plugin.ui.amazon_pay' => 'PwA',
                ],
                'label' => 'tierperso_sylius_amazon_pay_plugin.ui.button_type',
            ])
            ->add('buttonLanguage', ChoiceType::class, [
                'choices' => [
                    'tierperso_sylius_amazon_pay_plugin.ui.language_german' => 'de-DE',
                    'tierperso_sylius_amazon_pay_plugin.ui.language_english' => 'en-GB',
                    'tierperso_sylius_amazon_pay_plugin.ui.language_spanish' => 'es-ES',
                    'tierperso_sylius_amazon_pay_plugin.ui.language_french' => 'fr-FR',
                    'tierperso_sylius_amazon_pay_plugin.ui.language_italian' => 'it-IT',
                ],
                'label' => 'tierperso_sylius_amazon_pay_plugin.ui.button_language',
            ])
            ->add('environment', ChoiceType::class, [
                'choices' => [
                    'tierperso_sylius_amazon_pay_plugin.ui.production' => 'production',
                    'tierperso_sylius_amazon_pay_plugin.ui.sandbox' => 'sandbox',
                ],
                'label' => 'tierperso_sylius_amazon_pay_plugin.ui.environment',
            ])
            ->add('merchantId', TextType::class, [
                'label' => 'tierperso_sylius_amazon_pay_plugin.ui.merchant_id',
                'constraints' => [
                    new NotBlank([
                        'message' => 'tierperso_sylius_amazon_pay_plugin.merchant_id.not_blank',
                        'groups' => ['sylius'],
                    ]),
                ],
            ])
            ->add('accessKey', TextType::class, [
                'label' => 'tierperso_sylius_amazon_pay_plugin.ui.access_key',
                'constraints' => [
                    new NotBlank([
                        'message' => 'tierperso_sylius_amazon_pay_plugin.access_key.not_blank',
                        'groups' => ['sylius'],
                    ]),
                ],
            ])
            ->add('secretKey', TextType::class, [
                'label' => 'tierperso_sylius_amazon_pay_plugin.ui.secret_key',
                'constraints' => [
                    new NotBlank([
                        'message' => 'tierperso_sylius_amazon_pay_plugin.secret_key.not_blank',
                        'groups' => ['sylius'],
                    ]),
                ],
            ])
            ->add('clientId', TextType::class, [
                'label' => 'tierperso_sylius_amazon_pay_plugin.ui.client_id',
                'constraints' => [
                    new NotBlank([
                        'message' => 'tierperso_sylius_amazon_pay_plugin.client_id.not_blank',
                        'groups' => ['sylius'],
                    ]),
                ],
            ])
            ->add('region', ChoiceType::class, [
                'label' => 'tierperso_sylius_amazon_pay_plugin.ui.region',
                'choices' => [
                    'de' => 'de',
                    'uk' => 'uk',
                    'us' => 'us',
                    'jp' => 'jp',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'tierperso_sylius_amazon_pay_plugin.region.not_blank',
                        'groups' => ['sylius'],
                    ]),
                ],
            ])
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                $data = $event->getData();

                $data['payum.http_client'] = '@tierperso.sylius_amazon_pay_plugin.amazon_pay_api_client';

                $event->setData($data);
            })
        ;
    }
}
