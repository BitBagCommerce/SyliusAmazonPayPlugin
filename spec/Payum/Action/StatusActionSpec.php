<?php

declare(strict_types=1);

namespace spec\BitBag\SyliusAmazonPayPlugin\Payum\Action;

use Payum\Core\Action\ActionInterface;
use Payum\Core\GatewayAwareInterface;
use BitBag\SyliusAmazonPayPlugin\Payum\Action\StatusAction;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Request\GetStatusInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sylius\Component\Core\Model\PaymentInterface;

final class StatusActionSpec extends ObjectBehavior
{
    function it_is_initializable(): void
    {
        $this->shouldHaveType(StatusAction::class);
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
        GetStatusInterface $request
    ): void{
        $arrayObject = new ArrayObject(['amazon_pay']);
        $request->getModel()->willReturn($arrayObject);
        $request->markNew()->shouldBeCalled();

        $this->execute($request);
    }

    function it_supports_only_get_status_request_and_array_access(
        GetStatusInterface $request,
        \ArrayAccess $arrayAccess
    ): void {
        $request->getModel()->willReturn($arrayAccess);
        $this->supports($request)->shouldReturn(true);
    }
}
