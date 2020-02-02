<?php

declare(strict_types=1);

namespace App\Listener;

use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Throwable;

class BodyListener
{
    /**
     * @param RequestEvent $event
     */
    public function __invoke(RequestEvent $event)
    {
        $request = $event->getRequest();

        if ($request->getContentType() !== 'json') {
            return;
        }

        /** @var string $content */
        $content = $request->getContent();

        try {
            /** @var array $json */
            $json = json_decode($content, true, 512, JSON_THROW_ON_ERROR);
        } catch (Throwable $e) {
            throw new BadRequestHttpException('Invalid json.');
        }

        $request->request = new ParameterBag($json);

        if (empty($request->request->all())) {
            throw new BadRequestHttpException('Empty content.');
        }
    }
}
