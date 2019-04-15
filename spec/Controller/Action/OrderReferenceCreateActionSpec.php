<?php

declare(strict_types=1);

namespace spec\BitBag\SyliusAmazonPayPlugin\Controller\Action;

use AmazonPay\Client;
use Doctrine\ORM\EntityManagerInterface;
use Prophecy\Prophecy\ObjectProphecy;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\PaymentInterface;
use Sylius\Component\Core\Model\PaymentMethodInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use BitBag\SyliusAmazonPayPlugin\Client\AmazonPayApiClientInterface;
use BitBag\SyliusAmazonPayPlugin\Controller\Action\OrderReferenceCreateAction;
use Sylius\Component\Order\Context\CartContextInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

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
    ): void{
        $request->request = $parameterBag;
        $parameterBag->get('orderReferenceId')->willReturn('123');

        $cartContext->getCart()->willReturn($order);
        $order->getLastPayment()->willReturn($payment);
        $payment->getDetails()->willReturn(
            [
            'amazon_pay' => [
                'access_token' => '321'
            ]
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
