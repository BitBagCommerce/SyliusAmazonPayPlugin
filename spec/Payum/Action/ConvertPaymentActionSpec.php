<?php

declare(strict_types=1);

namespace spec\BitBag\SyliusAmazonPayPlugin\Payum\Action;

use BitBag\SyliusAmazonPayPlugin\Payum\Action\ConvertPaymentAction;
use Payum\Core\Action\ActionInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\GatewayAwareInterface;
use Payum\Core\Request\Convert;
use PhpSpec\ObjectBehavior;
use Sylius\Bundle\PayumBundle\Provider\PaymentDescriptionProviderInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\PaymentInterface;

final class ConvertPaymentActionSpec extends ObjectBehavior
{
    function let(PaymentDescriptionProviderInterface $paymentDescriptionProvider): void
    {
        $this->beConstructedWith($paymentDescriptionProvider);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(ConvertPaymentAction::class);
    }

    function it_implements_action_interface(): void
    {
        $this->shouldImplement(ActionInterface::class);
    }

    function it_implements_gateway_aware_interface(): void
    {
        $this->shouldImplement(GatewayAwareInterface::class);
    }

    function it_executes(
        Convert $request,
        ArrayObject $arrayObject,
        PaymentInterface $payment,
        OrderInterface $order
    ): void {
        $payment->getDetails()->willReturn([]);
        $payment->getOrder()->willReturn($order);

        $order->getCurrencyCode()->willReturn('code');
        $order->getNumber()->willReturn('number');
        $order->getTotal()->willReturn(10);

        $request->getSource()->willReturn($payment);
        $request->getTo()->willReturn('array');

        $request->setResult([
            'amazon_pay' => [
                'currency_code' => 'code',
                'order_number' => 'number',
                'total' => 0.1,
            ],
        ])->shouldBeCalled();

        $this->execute($request);
    }

    function it_supports_only_convert_request_payment_source_and_array_to(
        Convert $request,
        PaymentInterface $payment
    ): void {
        $request->getSource()->willReturn($payment);
        $request->getTo()->willReturn('array');
        $this->supports($request)->shouldReturn(true);
    }
}
