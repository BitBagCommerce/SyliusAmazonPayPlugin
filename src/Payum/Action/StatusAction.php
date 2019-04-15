<?php
declare(strict_types=1);

namespace BitBag\SyliusAmazonPayPlugin\Payum\Action;

use Payum\Core\Action\ActionInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\GatewayAwareInterface;
use Payum\Core\GatewayAwareTrait;
use Payum\Core\Request\GetStatusInterface;
use BitBag\SyliusAmazonPayPlugin\Client\AmazonPayApiClientInterface;

final class StatusAction implements ActionInterface, GatewayAwareInterface
{
    use GatewayAwareTrait;

    public function execute($request): void
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $details = ArrayObject::ensureArrayObject($request->getModel());

        if (!isset($details['amazon_pay']['status'])) {
            $request->markNew();

            return;
        }

        switch ($details['amazon_pay']['status']) {
            case AmazonPayApiClientInterface::STATUS_AUTHORIZED:
                $request->markCaptured();
                break;
            case AmazonPayApiClientInterface::STATUS_PROCESSING:
                $request->markPending();
                break;
            default:
                $request->markUnknown();
                break;
        }
    }
    public function supports($request): bool
    {
        return
            $request instanceof GetStatusInterface &&
            $request->getModel() instanceof \ArrayAccess
        ;
    }
}
