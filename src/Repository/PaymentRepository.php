<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
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
