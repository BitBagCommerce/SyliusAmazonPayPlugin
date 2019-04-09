<?php

declare(strict_types=1);

namespace Tierperso\SyliusAmazonPayPlugin\Repository;

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
