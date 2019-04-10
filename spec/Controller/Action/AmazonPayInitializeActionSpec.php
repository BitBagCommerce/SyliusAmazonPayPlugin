<?php

declare(strict_types=1);

namespace spec\Tierperso\SyliusAmazonPayPlugin\Controller\Action;

use Doctrine\ORM\EntityManagerInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\PaymentInterface;
use Sylius\Component\Core\Model\PaymentMethodInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Tierperso\SyliusAmazonPayPlugin\Controller\Action\AmazonPayInitializeAction;
use Tierperso\SyliusAmazonPayPlugin\Resolver\PaymentMethodResolverInterface;
use Tierperso\SyliusAmazonPayPlugin\Client\AmazonPayApiClientInterface;
use Sylius\Component\Order\Processor\OrderProcessorInterface;
use Sylius\Component\Order\Context\CartContextInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

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
            $orderProcessor ,
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
        CartContextInterface $cartContex,
        OrderInterface $order,
        PaymentInterface $payment,
        OrderProcessorInterface $orderProcessor,
        EntityManagerInterface $orderEntityManager,
        ParameterBag $parameterBag
    ): void{
        //$parameterBag->get('accessToken')->willReturn('123')->shouldBeCalled();
        //$request->request->get('accessToken')->willReturn('123')->shouldBeCalled();
        $request->getWrappedObject()->request->get('accessToken')->willReturn('123')->shouldBeCalled();

        $paymentMethodResolver->resolvePaymentMethod('amazonpay')->willReturn($paymentMethod);

        $cartContex->getCart()->willReturn($order);

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
