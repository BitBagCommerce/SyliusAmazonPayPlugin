<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusAmazonPayPlugin\Payum\Action;

use BitBag\SyliusAmazonPayPlugin\Client\AmazonPayApiClientInterface;
use BitBag\SyliusAmazonPayPlugin\Payum\Action\Api\ApiAwareTrait;
use Payum\Core\Action\ActionInterface;
use Payum\Core\ApiAwareInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\GatewayAwareInterface;
use Payum\Core\GatewayAwareTrait;
use Payum\Core\Request\Capture;

final class CaptureAction implements ActionInterface, GatewayAwareInterface, ApiAwareInterface
{
    use GatewayAwareTrait, ApiAwareTrait;

    public function execute($request): void
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $details = ArrayObject::ensureArrayObject($request->getModel());

        $amazonPayDetails = $details['amazon_pay'];

        if (!isset($amazonPayDetails['amazon_authorization_id'])) {
            return;
        }

        $authorizationDetailsResponse = $this->amazonPayApiClient->getClient()->getAuthorizationDetails([
            'amazon_authorization_id' => $amazonPayDetails['amazon_authorization_id'],
        ])->toArray();

        $authorizationStatus = $authorizationDetailsResponse['GetAuthorizationDetailsResult']['AuthorizationDetails']['AuthorizationStatus'];

        if (
            AmazonPayApiClientInterface::OPEN_AUTHORIZATION_STATUS === $authorizationStatus['State'] ||
            AmazonPayApiClientInterface::PENDING_AUTHORIZATION_STATUS === $authorizationStatus['State']
        ) {
            $amazonPayDetails['status'] = AmazonPayApiClientInterface::STATUS_PROCESSING;

            $details['amazon_pay'] = $amazonPayDetails;

            return;
        }

        if (AmazonPayApiClientInterface::DECLINED_AUTHORIZATION_STATUS === $authorizationStatus['State']) {
            $amazonPayDetails['status'] = AmazonPayApiClientInterface::STATUS_FAILED;
            $amazonPayDetails['error'] = $authorizationStatus['ReasonCode'];

            $details['amazon_pay'] = $amazonPayDetails;

            return;
        }

        if (
            AmazonPayApiClientInterface::CLOSED_AUTHORIZATION_STATUS === $authorizationStatus['State'] &&
            AmazonPayApiClientInterface::MAX_CAPTURES_PROCESSED_CODE === $authorizationStatus['ReasonCode']
        ) {
            $amazonPayDetails['status'] = AmazonPayApiClientInterface::STATUS_AUTHORIZED;

            $details['amazon_pay'] = $amazonPayDetails;

            return;
        }

        $amazonPayDetails['status'] = AmazonPayApiClientInterface::STATUS_FAILED;
        $amazonPayDetails['error'] = $authorizationStatus['ReasonCode'];

        $details['amazon_pay'] = $amazonPayDetails;
    }

    public function supports($request): bool
    {
        return
            $request instanceof Capture &&
            $request->getModel() instanceof \ArrayAccess
        ;
    }
}
