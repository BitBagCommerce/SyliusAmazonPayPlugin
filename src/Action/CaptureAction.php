<?php
declare(strict_types=1);

namespace Tierperso\SyliusAmazonPayPlugin\Action;

use Payum\Core\Action\ActionInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\GatewayAwareInterface;
use Payum\Core\GatewayAwareTrait;
use Payum\Core\Request\Capture;
use Payum\Core\Security\TokenInterface;

final class CaptureAction implements ActionInterface, GatewayAwareInterface
{
    use GatewayAwareTrait;
    /**
     * {@inheritdoc}
     *
     * @param Capture $request
     */
    public function execute($request): void
    {
        RequestNotSupportedException::assertSupports($this, $request);
        $details = ArrayObject::ensureArrayObject($request->getModel());

        if (true === isset($details['mws_auth_token'])) {
            return;
        }
        /** @var TokenInterface $token */
        $token = $request->getToken();

    }
    public function supports($request): bool
    {
        return
            $request instanceof Capture &&
            $request->getModel() instanceof \ArrayAccess
            ;
    }
}
