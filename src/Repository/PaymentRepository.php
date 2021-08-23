<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusAmazonPayPlugin\Repository;

use Sylius\Bundle\CoreBundle\Doctrine\ORM\PaymentRepository as BasePaymentRepository;

final class PaymentRepository extends BasePaymentRepository implements PaymentRepositoryInterface
{
    public function findAllByGatewayFactoryNameAndState(string $gatewayFactoryName, string $state): array
    {
        return $this->createQueryBuilder('o')
            ->innerJoin('o.method', 'method')
            ->innerJoin('method.gatewayConfig', 'gatewayConfig')
            ->where('gatewayConfig.factoryName = :gatewayFactoryName')
            ->andWhere('o.state = :state')
            ->setParameter('gatewayFactoryName', $gatewayFactoryName)
            ->setParameter('state', $state)
            ->getQuery()
            ->getResult()
        ;
    }
}
