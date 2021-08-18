<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
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
