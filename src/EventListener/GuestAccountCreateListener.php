<?php

declare(strict_types=1);

namespace BitBag\SyliusAmazonPayPlugin\EventListener;

use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Order\Context\CartContextInterface;
use Sylius\Component\Core\Model\PaymentInterface;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Model\ShopUserInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

final class GuestAccountCreateListener
{
    /** @var ContainerInterface */
    private $container;

    /** @var CartContextInterface */
    private $cartContext;

    public function __construct(
        ContainerInterface $container, CartContextInterface $cartContext
    ) {
        $this->container = $container;
        $this->cartContext = $cartContext;
    }

    public function addGuestAccount(): void
    {
        /** @var OrderInterface $order */
        $order = $this->cartContext->getCart();

        /** @var PaymentInterface $payment */
        $payment = $order->getLastPayment();

        $amazonEmail = $payment->getOrder()->getCustomer()->getEmail();

        /** @var ShopUserInterface $shopUser */
        $shopUser = $this->container->get('sylius.repository.shop_user')->findOneBy(['username' => $amazonEmail]);

        /** @var CustomerInterface $customer */
        $customer = $this->container->get('sylius.repository.customer')->findOneBy(['email' => $amazonEmail]);

        if(null === $shopUser && null !== $customer )
        {
            $password = bin2hex(random_bytes(9));

            /** @var ShopUserInterface $user */
            $user = $this->container->get('sylius.factory.shop_user')->createNew();

            $orderEmailManager = $this->container->get('sylius.email_manager.order');

            $user->setCustomer($customer);
            $user->setUsername($amazonEmail);
            $user->setPlainPassword($password);

            $this->container->get('sylius.repository.shop_user')->add($user);
        }
    }
}
