<?php

declare(strict_types=1);

namespace Tierperso\SyliusAmazonPayPlugin\Payum\Action;

use Payum\Core\Action\ActionInterface;
use Payum\Core\ApiAwareInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\GatewayAwareInterface;
use Payum\Core\GatewayAwareTrait;
use Payum\Core\Request\Capture;
use Tierperso\SyliusAmazonPayPlugin\Payum\Action\Api\ApiAwareTrait;

final class CaptureAction implements ActionInterface, GatewayAwareInterface, ApiAwareInterface
{
    use GatewayAwareTrait, ApiAwareTrait;

    public function execute($request): void
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $details = ArrayObject::ensureArrayObject($request->getModel());

        if (true === isset($details['mws_auth_token'])) {
            return;
        }

        $amazonOrderReferenceId = $details['amazon_pay']['amazon_order_reference_id'];

        $confirmOrderReferenceResponse = $this->amazonPayApiClient->getClient()->confirmOrderReference([
            'amazon_order_reference_id' => $amazonOrderReferenceId,
        ])->toArray();

        if (!$this->amazonPayApiClient->getClient()->success) {
            // todo
        }

        $authorizeResponse = $this->amazonPayApiClient->getClient()->authorize([
            'authorization_reference_id' => $details['amazon_pay']['order_number'],
            'amazon_order_reference_id' => $amazonOrderReferenceId,
            'authorization_amount' => $details['amazon_pay']['total'],
            'currency_code' => $details['amazon_pay']['currency_code'],
        ])->toArray();

        $amazonAuthorizationId = $authorizeResponse['AuthorizeResult']['AuthorizationDetails']['AmazonAuthorizationId'];

        $captureReferenceId = bin2hex(random_bytes(12));

        $captureResponse = $this->amazonPayApiClient->getClient()->capture([
            'amazon_authorization_id' => $amazonAuthorizationId,
            'capture_amount' => $details['amazon_pay']['total'],
            'capture_reference_id' => $captureReferenceId,
            'currency_code' => $details['amazon_pay']['currency_code'],
        ])->toArray();

        $orderReferenceDetailsResponse = $this->amazonPayApiClient->getClient()->getOrderReferenceDetails([
            'amazon_order_reference_id' => $amazonOrderReferenceId,
        ])->toArray();

        $closeOrderReferenceResponse = $this->amazonPayApiClient->getClient()->closeOrderReference([
            'amazon_order_reference_id' => $amazonOrderReferenceId,
        ])->toArray();

        // todo
    }
    public function supports($request): bool
    {
        return
            $request instanceof Capture &&
            $request->getModel() instanceof \ArrayAccess
        ;
    }
}
