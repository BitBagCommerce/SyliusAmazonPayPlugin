<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

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
