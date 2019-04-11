<?php

declare(strict_types=1);

namespace spec\BitBag\SyliusAmazonPayPlugin\Controller\Action;

use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\PaymentInterface;
use Sylius\Component\Core\Model\PaymentMethodInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use BitBag\SyliusAmazonPayPlugin\Controller\Action\AddressSelectAction;
use BitBag\SyliusAmazonPayPlugin\Client\AmazonPayApiClientInterface;
use Sylius\Component\Order\Context\CartContextInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

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

    function it_throw_exception_on_null_order_reference_id(Request $request): void
    {
        $request->request->get('orderReferenceId')->willReturn(null);

        $this->shouldThrow(new BadRequestHttpException());

        $this->__invoke($request);
    }

    function it_selects(
        Request $request,
        OrderInterface $order,
        PaymentInterface $payment,
        PaymentMethodInterface $paymentMethod,
        CartContextInterface $cartContext,
        AmazonPayApiClientInterface $amazonPayApiClient
    ): void {
        $request->request->get('orderReferenceId')->willReturn('123');

        $cartContext->getCart()->willReturn($order);

        $order->getLastPayment()->willReturn($payment);

        $payment->getDetails()->willReturn('123');
        $payment->getMethod()->willReturn($paymentMethod);

        $amazonPayApiClient->initializeFromPaymentMethod($paymentMethod)->shouldBeCalled();

        $this->__invoke($request);
    }
}
