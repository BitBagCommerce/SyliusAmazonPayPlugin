<?php

declare(strict_types=1);

namespace Tierperso\SyliusAmazonPayPlugin\Controller\Action;

use Doctrine\ORM\EntityManagerInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\PaymentInterface;
use Sylius\Component\Order\Context\CartContextInterface;
use Sylius\Component\Order\Processor\OrderProcessorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Tierperso\SyliusAmazonPayPlugin\AmazonPayGatewayFactory;
use Tierperso\SyliusAmazonPayPlugin\Client\AmazonPayApiClient;
use Tierperso\SyliusAmazonPayPlugin\Client\AmazonPayApiClientInterface;
use Tierperso\SyliusAmazonPayPlugin\Resolver\PaymentMethodResolverInterface;

final class AmazonPayInitializeAction
{
    /** @var CartContextInterface */
    private $cartContext;

    /** @var PaymentMethodResolverInterface  */
    private $paymentMethodResolver;

    /** @var AmazonPayApiClientInterface|AmazonPayApiClient */
    private $amazonPayApiClient;

    /** @var OrderProcessorInterface */
    private $orderProcessor;

    /** @var EntityManagerInterface */
    private $orderEntityManager;

    public function __construct(
        CartContextInterface $cartContext,
        PaymentMethodResolverInterface $paymentMethodResolver,
        AmazonPayApiClientInterface $amazonPayApiClient,
        OrderProcessorInterface $orderProcessor,
        EntityManagerInterface $orderEntityManager
    ) {
        $this->cartContext = $cartContext;
        $this->paymentMethodResolver = $paymentMethodResolver;
        $this->amazonPayApiClient = $amazonPayApiClient;
        $this->orderProcessor = $orderProcessor;
        $this->orderEntityManager = $orderEntityManager;
    }

    public function __invoke(Request $request): Response
    {
        $accessToken = $request->request->get('accessToken');

        $paymentMethod = $this->paymentMethodResolver->resolvePaymentMethod(AmazonPayGatewayFactory::FACTORY_NAME);

        if (!$accessToken || !$paymentMethod) {
            throw new BadRequestHttpException();
        }

        try {
            $this->amazonPayApiClient->initializeFromPaymentMethod($paymentMethod);

            $this->amazonPayApiClient->getClient()->getUserInfo($accessToken);
        } catch (\Exception $exception) {
            throw new BadRequestHttpException();
        }

        /** @var OrderInterface $order */
        $order = $this->cartContext->getCart();

        /** @var PaymentInterface $payment */
        $payment = $order->getLastPayment();

        $payment->setMethod($paymentMethod);

        $payment->setDetails(array_merge($payment->getDetails(), [
            'amazon_pay' => [
                'access_token' => $accessToken,
            ],
        ]));

        $this->orderProcessor->process($order);

        $this->orderEntityManager->flush();

        return new Response();
    }
}
