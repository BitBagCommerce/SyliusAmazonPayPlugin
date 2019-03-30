<?php

declare(strict_types=1);

namespace Tierperso\SyliusAmazonPayPlugin\Api;

use AmazonPay\Client;
use Payum\Core\Request\GetCurrency;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Tierperso\SyliusAmazonPayPlugin\Client\AmazonPayApiClientInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\PaymentInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

final class AmazonPayOrderDetailAction extends Controller
{
    public function __invoke(Request $request): Response
    {
        /** @var AmazonPayApiClientInterface $config */
        $config = $config->getConfig();

        $client = new Client($config);

        /** @var PaymentInterface $payment */
        $payment = $request->getSource();

        /** @var OrderInterface $order */
        $order = $payment->getOrder();

        $currency = new GetCurrency($payment->getCurrencyCode());

        $divisor = 10 ** $currency->exp;

        $amount = number_format(abs($payment->getAmount() / $divisor), 2, '.', '');

        $requestParameters = [];
        $requestParameters['amount']            =  $amount;
        $requestParameters['currency_code']     = 'EUR';
        $requestParameters['seller_order_id']   = $order->getId();
        $requestParameters['store_name']        = 'SDK Sample Store Name';
        $requestParameters['custom_information']= 'Any custom information';
        $requestParameters['mws_auth_token']    = null;
        $requestParameters['amazon_order_reference_id'] = $request->request->get('orderReferenceId');

        $payment->setDetails($requestParameters);

        $responseData = $client->getUserInfo($request->query->get('access_token'));

        $request->getSession()->set('amazon_order_reference_id', $request->request->get('orderReferenceId'));

        $order->setCustomer($responseData->toArray());

        return $this->redirectToRoute('sylius_shop_checkout_start');
    }
}
