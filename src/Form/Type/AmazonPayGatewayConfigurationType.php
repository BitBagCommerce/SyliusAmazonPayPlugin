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
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('buttonColor', ChoiceType::class, [
                'choices' => [
                    'tierperso_sylius_amazon_pay_plugin.ui.color_gold' => 'Gold',
                    'tierperso_sylius_amazon_pay_plugin.ui.color_light_gray' => 'LightGray',
                    'tierperso_sylius_amazon_pay_plugin.ui.color_dark_gray' => 'DarkGray',
                ],
                'label' => 'tierperso_sylius_amazon_pay_plugin.button_color',
            ])
            ->add('buttonSize', ChoiceType::class, [
                'choices' => [
                    'tierperso_sylius_amazon_pay_plugin.ui.small' => 'small',
                    'tierperso_sylius_amazon_pay_plugin.ui.medium' => 'medium',
                    'tierperso_sylius_amazon_pay_plugin.ui.large' => 'large',
                    'tierperso_sylius_amazon_pay_plugin.ui.x-large' => 'x-large',
                ],
                'label' => 'tierperso_sylius_amazon_pay_plugin.button_size',
            ])
            ->add('buttonType', ChoiceType::class, [
                'choices' => [
                    'tierperso_sylius_amazon_pay_plugin.login_ui.with_amazon' => 'LwA',
                    'tierperso_sylius_amazon_pay_plugin.ui.amazon_pay' => 'PwA',
                ],
                'label' => 'tierperso_sylius_amazon_pay_plugin.button_type',
            ])
            ->add('buttonLanguage', ChoiceType::class, [
                'choices' => [
                    'tierperso_sylius_amazon_pay_plugin.ui.german' => 'de-DE',
                    'tierperso_sylius_amazon_pay_plugin.ui.english' => 'en-GB',
                    'tierperso_sylius_amazon_pay_plugin.ui.spanish' => 'es-ES',
                    'tierperso_sylius_amazon_pay_plugin.ui.french' => 'fr-FR',
                    'tierperso_sylius_amazon_pay_plugin.ui.italian' => 'it-IT',
                ],
                'label' => 'tierperso_sylius_amazon_pay_plugin.button_language',
            ])
            ->add('environment', ChoiceType::class, [
                'choices' => [
                    'tierperso_sylius_amazon_pay_plugin.production' => 'production',
                    'tierperso_sylius_amazon_pay_plugin.sandbox' => 'sandbox',
                ],
                'label' => 'tierperso_sylius_amazon_pay_plugin.environment',
            ])
            ->add('merchant_id', TextType::class, [
                'label' => 'tierperso_sylius_amazon_pay_plugin.ui.merchant_id',
                'constraints' => [
                    new NotBlank([
                        'message' => 'tierperso_sylius_amazon_pay_plugin.merchant_id.not_blank',
                        'groups' => ['sylius'],
                    ]),
                ],
            ])
            ->add('access_key', TextType::class, [
                'label' => 'tierperso_sylius_amazon_pay_plugin.ui.access_key',
                'constraints' => [
                    new NotBlank([
                        'message' => 'tierperso_sylius_amazon_pay_plugin.access_key.not_blank',
                        'groups' => ['sylius'],
                    ]),
                ],
            ])
            ->add('secret_key', TextType::class, [
                'label' => 'tierperso_sylius_amazon_pay_plugin.ui.secret_key',
                'constraints' => [
                    new NotBlank([
                        'message' => 'tierperso_sylius_amazon_pay_plugin.secret_key.not_blank',
                        'groups' => ['sylius'],
                    ]),
                ],
            ])
            ->add('client_id', TextType::class, [
                'label' => 'tierperso_sylius_amazon_pay_plugin.ui.client_id',
                'constraints' => [
                    new NotBlank([
                        'message' => 'tierperso_sylius_amazon_pay_plugin.client_id.not_blank',
                        'groups' => ['sylius'],
                    ]),
                ],
            ])
            ->add('region', TextType::class, [
                'label' => 'tierperso_sylius_amazon_pay_plugin.ui.region',
                'constraints' => [
                    new NotBlank([
                        'message' => 'tierperso_sylius_amazon_pay_plugin.region.not_blank',
                        'groups' => ['sylius'],
                    ]),
                ],
            ])
        ;
    }
}
