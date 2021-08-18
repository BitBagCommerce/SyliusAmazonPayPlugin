<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace spec\BitBag\SyliusAmazonPayPlugin\Controller\Action;

use AmazonPay\Client;
use BitBag\SyliusAmazonPayPlugin\Client\AmazonPayApiClientInterface;
use BitBag\SyliusAmazonPayPlugin\Controller\Action\AmazonPayInitializeAction;
use BitBag\SyliusAmazonPayPlugin\Resolver\PaymentMethodResolverInterface;
use Doctrine\ORM\EntityManagerInterface;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\PaymentInterface;
use Sylius\Component\Core\Model\PaymentMethodInterface;
use Sylius\Component\Order\Context\CartContextInterface;
use Sylius\Component\Order\Processor\OrderProcessorInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\HttpFoundation\Request;

final class AmazonPayInitializeActionSpec extends ObjectBehavior
{
    function let(
        CartContextInterface $cartContext,
        PaymentMethodResolverInterface $paymentMethodResolver,
        AmazonPayApiClientInterface $amazonPayApiClient,
        OrderProcessorInterface $orderProcessor,
        EntityManagerInterface $orderEntityManager
    ): void {
        $this->beConstructedWith(
            $cartContext,
            $paymentMethodResolver,
            $amazonPayApiClient,
            $orderProcessor,
            $orderEntityManager
        );
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(AmazonPayInitializeAction::class);
    }

    function it_initialize(
        Request $request,
        PaymentMethodResolverInterface $paymentMethodResolver,
        PaymentMethodInterface $paymentMethod,
        CartContextInterface $cartContext,
        OrderInterface $order,
        PaymentInterface $payment,
        OrderProcessorInterface $orderProcessor,
        EntityManagerInterface $orderEntityManager,
        AmazonPayApiClientInterface $amazonPayApiClient,
        ParameterBag $parameterBag,
        Client $client
    ): void {
        $request->request = $parameterBag;
        $parameterBag->get('accessToken')->willReturn('123');

        $paymentMethodResolver->resolvePaymentMethod('amazonpay')->willReturn($paymentMethod);

        $amazonPayApiClient->initializeFromPaymentMethod($paymentMethod)->shouldBeCalled();
        $client->getUserInfo('123')->willReturn([]);
        $amazonPayApiClient->getClient()->willReturn($client);

        $cartContext->getCart()->willReturn($order);

        $order->getLastPayment()->willReturn($payment);

        $payment->setMethod($paymentMethod)->shouldBeCalled();
        $payment->getDetails()->willReturn([]);
        $payment->setDetails(array_merge([], [
            'amazon_pay' => [
                'access_token' => 123,
            ],
        ]))->shouldBeCalled();

        $orderProcessor->process($order)->shouldBeCalled();
        $orderEntityManager->flush()->shouldBeCalled();

        $this->__invoke($request);
    }
}
