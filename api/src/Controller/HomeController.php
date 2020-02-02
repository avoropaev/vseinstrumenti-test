<?php

declare(strict_types=1);

namespace App\Controller;

use JSend\InvalidJSendException;
use JSend\JSendResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     version="0.0.1",
 *     title="Test API",
 *     description="HTTP JSON API",
 * )
 */
class HomeController
{
    /**
     * @OA\Get(
     *     path="/ping",
     *     tags={"API"},
     *     description="API Check",
     *     @OA\Response(
     *         response="200",
     *         description="Success response",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", default="success"),
     *             @OA\Property(property="data", type="string", nullable="true", default="null")
     *         )
     *     )
     * )
     *
     * @return Response
     * @throws InvalidJSendException
     */
    public function ping(): Response
    {
        return new JsonResponse(JSendResponse::success(), Response::HTTP_OK);
    }
}
