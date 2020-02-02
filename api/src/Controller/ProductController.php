<?php

declare(strict_types=1);

namespace App\Controller;

use App\Exception\ValidationException;
use App\ReadModel\Product\ProductFetcher;
use App\Service\UseCase\Product\CreateRandom;
use JSend\InvalidJSendException;
use JSend\JSendResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use OpenApi\Annotations as OA;

class ProductController
{
    private ProductFetcher $productFetcher;
    private DenormalizerInterface $denormalizer;
    private ValidatorInterface $validator;
    private CreateRandom\Handler $createRandomHandler;

    /**
     * ProductController constructor.
     *
     * @param ProductFetcher        $productFetcher
     * @param DenormalizerInterface $denormalizer
     * @param ValidatorInterface    $validator
     * @param CreateRandom\Handler  $createRandomHandler
     */
    public function __construct(
        ProductFetcher $productFetcher,
        DenormalizerInterface $denormalizer,
        ValidatorInterface $validator,
        CreateRandom\Handler $createRandomHandler
    ) {
        $this->productFetcher = $productFetcher;
        $this->denormalizer = $denormalizer;
        $this->validator = $validator;
        $this->createRandomHandler = $createRandomHandler;
    }

    /**
     * @OA\Post(
     *     path="/products/create_random",
     *     tags={"Products"},
     *     description="Create random products",
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="count", type="integer", minimum="1", maximum="20")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Success response"
     *     )
     * )
     *
     * @param Request $request
     *
     * @return Response
     * @throws InvalidJSendException
     * @throws ExceptionInterface
     */
    public function createRandom(Request $request): Response
    {
        $data = $request->request->all();

        $violations = $this->validator->validate($data, new Assert\Collection([
            'count' => [
                new Assert\NotBlank(),
                new Assert\Type('int'),
                new Assert\GreaterThanOrEqual(1),
                new Assert\LessThanOrEqual(20)
            ]
        ]));

        if ($violations->count() > 0) {
            throw new ValidationException($violations);
        }

        /** @var CreateRandom\Command $command */
        $command = $this->denormalizer->denormalize($data, CreateRandom\Command::class);
        $this->createRandomHandler->handle($command);

        return new JsonResponse(JSendResponse::success(), Response::HTTP_CREATED);
    }

    /**
     * @OA\Get(
     *     path="/products",
     *     tags={"Products"},
     *     description="Get all products",
     *     @OA\Response(
     *         response=200,
     *         description="Success response",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", default="success"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="products", type="array",
     *                      @OA\Items(
     *                          @OA\Property(property="id", type="integer"),
     *                          @OA\Property(property="name", type="string"),
     *                          @OA\Property(property="price", type="integer")
     *                      )
     *                 )
     *             )
     *         )
     *     )
     * )
     *
     * @return Response
     * @throws InvalidJSendException
     */
    public function getAll(): Response
    {
        return new JsonResponse(JSendResponse::success([
            'products' => $this->productFetcher->all()
        ]), Response::HTTP_OK);
    }
}
