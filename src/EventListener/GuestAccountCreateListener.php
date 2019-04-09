<?php
//
//declare(strict_types=1);
//
//namespace Tierperso\SyliusAmazonPayPlugin\EventListener;
//
//use Sylius\Component\Core\Model\CustomerInterface;
//use Sylius\Component\Core\Model\ShopUserInterface;
//use Symfony\Component\DependencyInjection\ContainerInterface;
//
//final class GuestAccountCreateListener
//{
//    /** @var ShopUserInterface */
//    private $user;
//
//    /** @var CustomerInterface */
//    private $customer;
//
//    /** @var ContainerInterface */
//    private $container;
//
//    public function __construct(
//        ShopUserInterface $user,
//        CustomerInterface $customer,
//        ContainerInterface $container
//    ) {
//        $this->user = $user;
//        $this->customer = $customer;
//        $this->container = $container;
//    }
//
//    public function addGuestAccount(): void
//    {
//        $userEmail = $this->user->getEmail();
//
//        $customerEmail = $this->customer->getEmail();
//
//        if($customerEmail !== $userEmail)
//        {
//            $password = substr(md5(mt_rand()), 0, 9);
//
//            /** @var ShopUserInterface $user */
//            $user = $this->container->get('sylius.factory.shop_user')->createNew();
//
//            /** @var CustomerInterface $customer */
//            $customer = $this->container->get('sylius.repository.customer')->findOneBy(['email' => $customerEmail]);
//            $orderEmailManager = $this->container->get('sylius.email_manager.order');
//
//            $this->user->setCustomer($customer);
//            $this->user->setEmail($customerEmail);
//            $this->user->setPlainPassword($password);
//
//            $this->container->get('sylius.repository.shop_user')->add($user);
//
//            $orderEmailManager->sendConfirmationEmail();
//        }
//    }
//
//}
