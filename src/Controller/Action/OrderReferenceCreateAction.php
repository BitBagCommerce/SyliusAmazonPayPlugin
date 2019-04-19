<?php

declare(strict_types=1);

namespace BitBag\SyliusAmazonPayPlugin\Controller\Action;

use BitBag\SyliusAmazonPayPlugin\Client\AmazonPayApiClientInterface;
use Doctrine\ORM\EntityManagerInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\PaymentInterface;
use Sylius\Component\Core\Model\PaymentMethodInterface;
use Sylius\Component\Order\Context\CartContextInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

final class OrderReferenceCreateAction
{
    /** @var CartContextInterface */
    private $cartContext;

    /** @var AmazonPayApiClientInterface */
    private $amazonPayApiClient;

    /** @var EntityManagerInterface */
    private $orderEntityManager;

    public function __construct(
        CartContextInterface $cartContext,
        AmazonPayApiClientInterface $amazonPayApiClient,
        EntityManagerInterface $orderEntityManager
    ) {
        $this->cartContext = $cartContext;
        $this->amazonPayApiClient = $amazonPayApiClient;
        $this->orderEntityManager = $orderEntityManager;
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
            'amount' => $order->getTotal() / 100,
            'currency_code' => $order->getCurrencyCode(),
            'seller_order_id' => $order->getNumber(),
            'mws_auth_token' => null,
            'amazon_order_reference_id' => $orderReferenceId,
            'access_token' => $accessToken,
        ];

        $this->amazonPayApiClient->getClient()->setOrderReferenceDetails($requestParameters);

        $payment->setDetails(array_merge($payment->getDetails(), [
            'amazon_pay' => [
                'amazon_order_reference_id' => $orderReferenceId,
                'access_token' => $accessToken,
            ],
        ]));

        $this->orderEntityManager->flush();

        return new Response();
    }
}
