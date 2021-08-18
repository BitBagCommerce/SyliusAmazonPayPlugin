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
use BitBag\SyliusAmazonPayPlugin\Controller\Action\OrderReferenceCreateAction;
use Doctrine\ORM\EntityManagerInterface;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\PaymentInterface;
use Sylius\Component\Core\Model\PaymentMethodInterface;
use Sylius\Component\Order\Context\CartContextInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;

final class OrderReferenceCreateActionSpec extends ObjectBehavior
{
    function let(
        CartContextInterface $cartContext,
        AmazonPayApiClientInterface $amazonPayApiClient,
        EntityManagerInterface $orderEntityManager
    ): void {
        $this->beConstructedWith(
            $cartContext,
            $amazonPayApiClient,
            $orderEntityManager
        );
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(OrderReferenceCreateAction::class);
    }

    function it_create_order_reference(
        Request $request,
        OrderInterface $order,
        PaymentInterface $payment,
        PaymentMethodInterface $paymentMethod,
        CartContextInterface $cartContext,
        AmazonPayApiClientInterface $amazonPayApiClient,
        EntityManagerInterface $orderEntityManager,
        ParameterBag $parameterBag,
        Client $client
    ): void {
        $request->request = $parameterBag;
        $parameterBag->get('orderReferenceId')->willReturn('123');

        $cartContext->getCart()->willReturn($order);
        $order->getLastPayment()->willReturn($payment);
        $payment->getDetails()->willReturn(
            [
            'amazon_pay' => [
                'access_token' => '321',
            ],
        ]);
        $payment->getMethod()->willReturn($paymentMethod);

        $amazonPayApiClient->initializeFromPaymentMethod($paymentMethod);

        $order->getTotal()->willReturn(10);
        $order->getCurrencyCode()->willReturn('3');
        $order->getNumber()->willReturn('1');

        $client->setOrderReferenceDetails([
            'amount' => 0.1,
            'currency_code' => '3',
            'seller_order_id' => '1',
            'mws_auth_token' => null,
            'amazon_order_reference_id' => '123',
            'access_token' => '321',
        ])->willReturn([]);
        $amazonPayApiClient->getClient()->willReturn($client);

        $orderEntityManager->flush();
    }
}
