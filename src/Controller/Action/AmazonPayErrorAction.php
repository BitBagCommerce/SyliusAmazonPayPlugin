<?php

declare(strict_types=1);

namespace BitBag\SyliusAmazonPayPlugin\Controller\Action;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

final class AmazonPayErrorAction
{
    public function __invoke(Request $request): Response
    {
        if (!$request->request->has('message')) {
            throw new BadRequestHttpException();
        }

        /** @var FlashBagInterface $flashBag */
        $flashBag = $request->getSession()->getBag('flashes');

        $flashBag->add('error', $request->request->get('message'));

        return new Response();
    }
}
