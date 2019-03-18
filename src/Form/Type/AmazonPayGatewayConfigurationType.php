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
