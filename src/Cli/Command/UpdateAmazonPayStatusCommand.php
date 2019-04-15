<?php

declare(strict_types=1);

namespace BitBag\SyliusAmazonPayPlugin\Cli\Command;

use Sylius\Component\Core\Model\PaymentInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use BitBag\SyliusAmazonPayPlugin\AmazonPayGatewayFactory;
use BitBag\SyliusAmazonPayPlugin\Repository\PaymentRepositoryInterface;
use BitBag\SyliusAmazonPayPlugin\Resolver\PaymentStateResolverInterface;

final class UpdateAmazonPayStatusCommand extends Command
{
    /** @var PaymentRepositoryInterface */
    private $paymentRepository;

    /** @var PaymentStateResolverInterface */
    private $paymentStateResolver;

    public function __construct(
        PaymentRepositoryInterface $paymentRepository,
        PaymentStateResolverInterface $paymentStateResolver
    ) {
        parent::__construct();

        $this->paymentRepository = $paymentRepository;
        $this->paymentStateResolver = $paymentStateResolver;
    }

    protected function configure(): void
    {
        $this
            ->setName('bitbag:amazon-pay:update-payment-state')
            ->setDescription('Updates the payments state.')
            ->setHelp('This command allows you to update the payments state for Amazon Pay gateway.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $payments = $this->paymentRepository->findAllByGatewayFactoryNameAndState(
            AmazonPayGatewayFactory::FACTORY_NAME,
            PaymentInterface::STATE_PROCESSING
        );

        foreach ($payments as $payment) {
            $this->paymentStateResolver->resolve($payment);
        }
    }
}
