<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace spec\BitBag\SyliusAmazonPayPlugin\Payum\Action;

use BitBag\SyliusAmazonPayPlugin\Payum\Action\StatusAction;
use Payum\Core\Action\ActionInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\GatewayAwareInterface;
use Payum\Core\Request\GetStatusInterface;
use PhpSpec\ObjectBehavior;

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
    ): void {
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
