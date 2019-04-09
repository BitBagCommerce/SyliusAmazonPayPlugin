<?php
declare(strict_types=1);

namespace spec\Tierperso\SyliusAmazonPayPlugin\Controller\Action;

use Doctrine\ORM\EntityManagerInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\PaymentInterface;
use Sylius\Component\Core\Model\PaymentMethodInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Tierperso\SyliusAmazonPayPlugin\Client\AmazonPayApiClientInterface;
use Tierperso\SyliusAmazonPayPlugin\Controller\Action\OrderReferenceCreateAction;
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

    function it_throw_exception_on_null_order_reference_id(Request $request): void
    {
        $request->request->get('orderReferenceId')->willReturn(null);

        $this->shouldThrow(new BadRequestHttpException());

        $this->__invoke($request);
    }

    function it_create_order_reference(
        Request $request,
        OrderInterface $order,
        PaymentInterface $payment,
        PaymentMethodInterface $paymentMethod,
        CartContextInterface $cartContext,
        AmazonPayApiClientInterface $amazonPayApiClient,
        EntityManagerInterface $orderEntityManager
    ): void{

        $request->request->get('orderReferenceId')->willReturn('123');
        $cartContext->getCart()->willReturn($order);
        $order->getLastPayment()->willReturn($payment);
        $payment->getMethod()->willReturn($paymentMethod);

        $amazonPayApiClient->initializeFromPaymentMethod($paymentMethod)->shouldBeCalled();

        $order->getTotal()->willReturn('10');
        $order->getCurrencyCode()->willReturn('3');
        $order->getNumber()->willReturn('1');

        $amazonPayApiClient->getClient()->setOrderReferenceDetails([
            'amount' => '10',
            'currency_code' => '3',
            'seller_order_id' => '1',
            'mws_auth_token' => null,
            'amazon_order_reference_id' => '123',
            'access_token' => '321',
        ])->shouldBeCalled();

        $orderEntityManager->flush();
    }
}
