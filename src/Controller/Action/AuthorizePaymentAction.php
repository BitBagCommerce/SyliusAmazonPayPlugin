<?php

declare(strict_types=1);

namespace BitBag\SyliusAmazonPayPlugin\Controller\Action;

use BitBag\SyliusAmazonPayPlugin\Client\AmazonPayApiClientInterface;
use Doctrine\ORM\EntityManagerInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\PaymentInterface;
use Sylius\Component\Core\Model\PaymentMethodInterface;
use Sylius\Component\Order\Context\CartContextInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Contracts\Translation\TranslatorInterface;

final class AuthorizePaymentAction
{
    /** @var CartContextInterface */
    private $cartContext;

    /** @var AmazonPayApiClientInterface */
    private $amazonPayApiClient;

    /** @var EntityManagerInterface */
    private $orderEntityManager;

    /** @var TranslatorInterface */
    private $translator;

    public function __construct(
        CartContextInterface $cartContext,
        AmazonPayApiClientInterface $amazonPayApiClient,
        EntityManagerInterface $orderEntityManager,
        TranslatorInterface $translator
    ) {
        $this->cartContext = $cartContext;
        $this->amazonPayApiClient = $amazonPayApiClient;
        $this->orderEntityManager = $orderEntityManager;
        $this->translator = $translator;
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

        $details = $payment->getDetails();

        /** @var PaymentMethodInterface $paymentMethod */
        $paymentMethod = $payment->getMethod();

        $this->amazonPayApiClient->initializeFromPaymentMethod($paymentMethod);

        $this->amazonPayApiClient->getClient()->setOrderReferenceDetails([
            'amount' => $order->getTotal() / 100,
            'currency_code' => $order->getCurrencyCode(),
            'seller_order_id' => $order->getNumber(),
            'mws_auth_token' => null,
            'amazon_order_reference_id' => $orderReferenceId,
        ]);

        $confirmOrderReferenceResponse = $this->amazonPayApiClient->getClient()->confirmOrderReference([
            'amazon_order_reference_id' => $orderReferenceId,
        ])->toArray();

        /** @var FlashBagInterface $flashBag */
        $flashBag = $request->getSession()->getBag('flashes');

        if (isset($confirmOrderReferenceResponse['Error']['Message'])) {
            $flashBag->add('error', $this->translator->trans('bitbag_sylius_amazon_pay_plugin.ui.invalid_payment_method'));

            return new JsonResponse(
                'Payment method invalid',
                Response::HTTP_BAD_REQUEST
            );
        }

        $authorizationReferenceId = bin2hex(random_bytes(12));

        $authorizeResponse = $this->amazonPayApiClient->getClient()->authorize([
            'authorization_reference_id' => $authorizationReferenceId,
            'amazon_order_reference_id' => $orderReferenceId,
            'authorization_amount' => $order->getTotal() / 100,
            'currency_code' => $order->getCurrencyCode(),
            'capture_now' => true,
            'transaction_timeout' => 0,
        ])->toArray();

        $authorizationStatus = $authorizeResponse['AuthorizeResult']['AuthorizationDetails']['AuthorizationStatus'];

        if ($authorizationStatus['State'] === AmazonPayApiClientInterface::DECLINED_AUTHORIZATION_STATUS) {
            if ($authorizationStatus['ReasonCode'] === 'InvalidPaymentMethod') {
                $flashBag->add('error', $this->translator->trans('bitbag_sylius_amazon_pay_plugin.ui.invalid_payment_method'));

                return new JsonResponse(
                    'Payment method invalid',
                    Response::HTTP_BAD_REQUEST
                );
            }

            if ($authorizationStatus['ReasonCode'] === AmazonPayApiClientInterface::TRANSACTION_TIMED_OUT_ERROR_CODE) {
                $authorizationReferenceId = bin2hex(random_bytes(12));

                $authorizeResponse = $this->amazonPayApiClient->getClient()->authorize([
                    'authorization_reference_id' => $authorizationReferenceId,
                    'amazon_order_reference_id' => $orderReferenceId,
                    'authorization_amount' => $order->getTotal() / 100,
                    'currency_code' => $order->getCurrencyCode(),
                    'capture_now' => true,
                    'transaction_timeout' => 1440,
                ])->toArray();

                $details['amazon_pay']['status'] = AmazonPayApiClientInterface::STATUS_PROCESSING;
                $details['amazon_pay']['amazon_authorization_id'] = $authorizeResponse['AuthorizeResult']['AuthorizationDetails']['AmazonAuthorizationId'];
                $details['amazon_pay']['authorization_reference_id'] = $authorizeResponse['AuthorizeResult']['AuthorizationDetails']['AuthorizationReferenceId'];

                $payment->setDetails($details);

                $this->orderEntityManager->flush();

                return new JsonResponse([]);
            }

            $orderReferenceDetailsResponse = $this->amazonPayApiClient->getClient()->getOrderReferenceDetails([
                'amazon_order_reference_id' => $orderReferenceId,
            ])->toArray();

            $orderReferenceStatus = $orderReferenceDetailsResponse['GetOrderReferenceDetailsResult']['OrderReferenceDetails']['OrderReferenceStatus'];

            if ($orderReferenceStatus['State'] === AmazonPayApiClientInterface::OPEN_ORDER_REFERENCE_STATUS) {
                $this->amazonPayApiClient->getClient()->cancelOrderReference([
                    'amazon_order_reference_id' => $orderReferenceId,
                ]);
            }

            $flashBag->add('error', $this->translator->trans('bitbag_sylius_amazon_pay_plugin.ui.failed_payment'));

            return new JsonResponse(
                'Failed payment',
                Response::HTTP_BAD_REQUEST
            );
        }

        $details['amazon_pay']['status'] = AmazonPayApiClientInterface::STATUS_AUTHORIZED;
        $details['amazon_pay']['amazon_authorization_id'] = $authorizeResponse['AuthorizeResult']['AuthorizationDetails']['AmazonAuthorizationId'];
        $details['amazon_pay']['authorization_reference_id'] = $authorizeResponse['AuthorizeResult']['AuthorizationDetails']['AuthorizationReferenceId'];

        $payment->setDetails($details);

        $this->orderEntityManager->flush();

        return new JsonResponse([]);
    }
}
