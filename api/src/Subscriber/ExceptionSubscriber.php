<?php

namespace App\Subscriber;

use App\Exception\ValidationException;
use App\Service\ValidationFormatter;
use DomainException;
use Jawira\CaseConverter\CaseConverterException;
use JSend\InvalidJSendException;
use JSend\JSendResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class ExceptionSubscriber implements EventSubscriberInterface
{
    private ValidationFormatter $validationFormatter;

    /**
     * ExceptionSubscriber constructor.
     *
     * @param ValidationFormatter $validationFormatter
     */
    public function __construct(ValidationFormatter $validationFormatter)
    {
        $this->validationFormatter = $validationFormatter;
    }

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => ['onKernelException', 128],
        ];
    }

    /**
     * @param ExceptionEvent $event
     *
     * @throws InvalidJSendException
     * @throws CaseConverterException
     */
    public function onKernelException(ExceptionEvent $event): void
    {
        $e = $event->getThrowable();
        $trace = 'dev' === $_SERVER['APP_ENV'] ? $e->getTrace() : null;

        if ($e instanceof ValidationException) {
            $errors = $this->validationFormatter->format($e->violationList());
            $data = JSendResponse::fail($errors);

            $event->setResponse(new JsonResponse($data, Response::HTTP_BAD_REQUEST));
        } elseif ($e instanceof HttpException) {
            $data = JSendResponse::error(
                $e->getMessage() ?: 'Unknown error.',
                (string) $e->getStatusCode(),
                $trace
            );

            $event->setResponse(new JsonResponse($data, $e->getStatusCode()));
        } elseif ($e instanceof DomainException) {
            $data = JSendResponse::error(
                $e->getMessage() ?: 'Bad request.',
                (string) Response::HTTP_BAD_REQUEST,
                $trace
            );

            $event->setResponse(new JsonResponse($data, Response::HTTP_BAD_REQUEST));
        } else {
            $errorMessage = $e->getMessage() ?: 'Internal server error.';
            $data = JSendResponse::error(
                'dev' === $_SERVER['APP_ENV'] ? $errorMessage : 'Internal server error.',
                (string) Response::HTTP_INTERNAL_SERVER_ERROR,
                $trace
            );

            $event->setResponse(new JsonResponse($data, Response::HTTP_INTERNAL_SERVER_ERROR));
        }
    }
}
