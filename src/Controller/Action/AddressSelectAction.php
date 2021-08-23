<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusAmazonPayPlugin\Controller\Action;

use BitBag\SyliusAmazonPayPlugin\Client\AmazonPayApiClientInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\PaymentInterface;
use Sylius\Component\Core\Model\PaymentMethodInterface;
use Sylius\Component\Order\Context\CartContextInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

final class AddressSelectAction
{
    /** @var CartContextInterface */
    private $cartContext;

    /** @var AmazonPayApiClientInterface */
    private $amazonPayApiClient;

    public function __construct(CartContextInterface $cartContext, AmazonPayApiClientInterface $amazonPayApiClient)
    {
        $this->cartContext = $cartContext;
        $this->amazonPayApiClient = $amazonPayApiClient;
    }

    public function __invoke(Request $request): Response
    {
        $orderReferenceId = $request->request->get('orderReferenceId');

        if (!$orderReferenceId) {
            throw new BadRequestHttpException();
        }

        /** @var OrderInterface $order */
        $order = $this->cartContext->getCart();

        /** @var PaymentInterface $payment */
        $payment = $order->getLastPayment();

        $accessToken = $payment->getDetails()['amazon_pay']['access_token'];

        /** @var PaymentMethodInterface $paymentMethod */
        $paymentMethod = $payment->getMethod();

        $this->amazonPayApiClient->initializeFromPaymentMethod($paymentMethod);

        $requestParameters = [
            'mws_auth_token' => null,
            'amazon_order_reference_id' => $orderReferenceId,
            'access_token' => $accessToken,
        ];

        $orderReferenceDetails = $this->amazonPayApiClient->getClient()->getOrderReferenceDetails($requestParameters)->toArray();

        return new JsonResponse([
            'address' => $orderReferenceDetails['GetOrderReferenceDetailsResult']['OrderReferenceDetails']['Destination'],
            'buyer' => $orderReferenceDetails['GetOrderReferenceDetailsResult']['OrderReferenceDetails']['Buyer'],
        ]);
    }
}
