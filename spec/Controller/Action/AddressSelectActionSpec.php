<?php

declare(strict_types=1);

namespace spec\BitBag\SyliusAmazonPayPlugin\Controller\Action;

use AmazonPay\Client;
use AmazonPay\ResponseParser;
use BitBag\SyliusAmazonPayPlugin\Client\AmazonPayApiClientInterface;
use BitBag\SyliusAmazonPayPlugin\Controller\Action\AddressSelectAction;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\PaymentInterface;
use Sylius\Component\Core\Model\PaymentMethodInterface;
use Sylius\Component\Order\Context\CartContextInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;

final class AddressSelectActionSpec extends ObjectBehavior
{
    function let(
        CartContextInterface $cartContext,
        AmazonPayApiClientInterface $amazonPayApiClient
    ): void {
        $this->beConstructedWith(
            $cartContext,
            $amazonPayApiClient
        );
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(AddressSelectAction::class);
    }

    function it_selects(
        Request $request,
        OrderInterface $order,
        PaymentInterface $payment,
        PaymentMethodInterface $paymentMethod,
        CartContextInterface $cartContext,
        AmazonPayApiClientInterface $amazonPayApiClient,
        ParameterBag $parameterBag,
        Client $client,
        ResponseParser $parser
    ): void {
        $request->request = $parameterBag;
        $parameterBag->get('orderReferenceId')->willReturn(123);

        $cartContext->getCart()->willReturn($order);

        $order->getLastPayment()->willReturn($payment);

        $payment->getDetails()->willReturn([
            'amazon_pay' => [
                'access_token' => '123',
            ],
        ]);
        $payment->getMethod()->willReturn($paymentMethod);

        $amazonPayApiClient->initializeFromPaymentMethod($paymentMethod)->shouldBeCalled();

        $client->getOrderReferenceDetails([
            'mws_auth_token' => null,
            'amazon_order_reference_id' => 123,
            'access_token' => '123',
        ])->willReturn($parser);

        $amazonPayApiClient->getClient()->willReturn($client);

        $this->__invoke($request);
    }
}
