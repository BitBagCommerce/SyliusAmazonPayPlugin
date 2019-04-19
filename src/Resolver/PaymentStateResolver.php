<?php

declare(strict_types=1);

namespace BitBag\SyliusAmazonPayPlugin\Resolver;

use BitBag\SyliusAmazonPayPlugin\Client\AmazonPayApiClientInterface;
use Doctrine\ORM\EntityManagerInterface;
use SM\Factory\FactoryInterface;
use Sylius\Component\Core\Model\PaymentInterface;
use Sylius\Component\Core\Model\PaymentMethodInterface;
use Sylius\Component\Payment\PaymentTransitions;

final class PaymentStateResolver implements PaymentStateResolverInterface
{
    /** @var FactoryInterface */
    private $stateMachineFactory;

    /** @var AmazonPayApiClientInterface */
    private $amazonPayApiClient;

    /** @var EntityManagerInterface */
    private $paymentEntityManager;

    public function __construct(
        FactoryInterface $stateMachineFactory,
        AmazonPayApiClientInterface $amazonPayApiClient,
        EntityManagerInterface $paymentEntityManager
    ) {
        $this->stateMachineFactory = $stateMachineFactory;
        $this->amazonPayApiClient = $amazonPayApiClient;
        $this->paymentEntityManager = $paymentEntityManager;
    }

    public function resolve(PaymentInterface $payment): void
    {
        /** @var PaymentMethodInterface $paymentMethod */
        $paymentMethod = $payment->getMethod();

        $this->amazonPayApiClient->initializeFromPaymentMethod($paymentMethod);

        $details = $payment->getDetails();

        $amazonPayDetails = $details['amazon_pay'];

        $authorizationDetailsResponse = $this->amazonPayApiClient->getClient()->getAuthorizationDetails([
            'amazon_authorization_id' => $amazonPayDetails['amazon_authorization_id'],
        ])->toArray();

        $authorizationStatus = $authorizationDetailsResponse['GetAuthorizationDetailsResult']['AuthorizationDetails']['AuthorizationStatus'];

        $paymentStateMachine = $this->stateMachineFactory->get($payment, PaymentTransitions::GRAPH);

        if (AmazonPayApiClientInterface::DECLINED_AUTHORIZATION_STATUS === $authorizationStatus['State']) {
            $amazonPayDetails['status'] = AmazonPayApiClientInterface::STATUS_FAILED;
            $amazonPayDetails['error'] = $authorizationStatus['ReasonCode'];

            $details['amazon_pay'] = $amazonPayDetails;

            if ($paymentStateMachine->can(PaymentTransitions::TRANSITION_FAIL)) {
                $paymentStateMachine->apply(PaymentTransitions::TRANSITION_FAIL);
            }
        }

        if (
            AmazonPayApiClientInterface::CLOSED_AUTHORIZATION_STATUS === $authorizationStatus['State'] &&
            AmazonPayApiClientInterface::MAX_CAPTURES_PROCESSED_CODE === $authorizationStatus['ReasonCode']
        ) {
            $amazonPayDetails['status'] = AmazonPayApiClientInterface::STATUS_AUTHORIZED;

            $details['amazon_pay'] = $amazonPayDetails;

            if ($paymentStateMachine->can(PaymentTransitions::TRANSITION_COMPLETE)) {
                $paymentStateMachine->apply(PaymentTransitions::TRANSITION_COMPLETE);
            }
        }

        $this->paymentEntityManager->flush();
    }
}
