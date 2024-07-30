<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusAmazonPayPlugin\Repository;

use Sylius\Bundle\CoreBundle\Doctrine\ORM\PaymentMethodRepository as BasePaymentMethodRepository;
use Sylius\Component\Channel\Model\ChannelInterface;

final class PaymentMethodRepository extends BasePaymentMethodRepository implements PaymentMethodRepositoryInterface
{
    public function findAllEnabledByFactoryNameAndChannel(string $name, ChannelInterface $channel): array
    {
        return $this->createQueryBuilder('o')
            ->innerJoin('o.gatewayConfig', 'gatewayConfig')
            ->andWhere(':channel MEMBER OF o.channels')
            ->andWhere('gatewayConfig.factoryName = :name')
            ->andWhere('o.enabled = true')
            ->setParameter('name', $name)
            ->setParameter('channel', $channel)
            ->addOrderBy('o.position')
            ->getQuery()
            ->getResult()
        ;
    }
}
