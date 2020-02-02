<?php

declare(strict_types=1);

namespace App\Controller;

use App\Exception\ValidationException;
use App\ReadModel\Order\OrderFetcher;
use JSend\InvalidJSendException;
use JSend\JSendResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Service\UseCase\Order\Create;
use App\Service\UseCase\Order\Pay;
use OpenApi\Annotations as OA;

class OrderController
{
    private OrderFetcher $orderFetcher;
    private DenormalizerInterface $denormalizer;
    private ValidatorInterface $validator;
    private Create\Handler $createHandler;
    private Pay\Handler $payHandler;

    /**
     * OrderController constructor.
     *
     * @param OrderFetcher          $orderFetcher
     * @param DenormalizerInterface $denormalizer
     * @param ValidatorInterface    $validator
     * @param Create\Handler        $createHandler
     * @param Pay\Handler           $payHandler
     */
    public function __construct(
        OrderFetcher $orderFetcher,
        DenormalizerInterface $denormalizer,
        ValidatorInterface $validator,
        Create\Handler $createHandler,
        Pay\Handler $payHandler
    ) {
        $this->denormalizer = $denormalizer;
        $this->validator = $validator;
        $this->createHandler = $createHandler;
        $this->orderFetcher = $orderFetcher;
        $this->payHandler = $payHandler;
    }

    /**
     * @OA\Post(
     *     path="/orders",
     *     tags={"Orders"},
     *     description="Create order",
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="products_ids", type="array",
     *                  @OA\Items(type="integer")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Success response",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", default="success"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="order_id", type="integer")
     *             )
     *         )
     *     )
     * )
     *
     * @param Request $request
     *
     * @return Response
     * @throws InvalidJSendException
     * @throws ExceptionInterface
     */
    public function create(Request $request): Response
    {
        $data = $request->request->all();

        $violations = $this->validator->validate($data, new Assert\Collection([
            'products_ids' => [
                new Assert\NotBlank(),
                new Assert\Type('array'),
                new Assert\All([
                    new Assert\NotBlank(),
                    new Assert\Type('int'),
                    new Assert\Positive()
                ])
            ]
        ]));

        if ($violations->count() > 0) {
            throw new ValidationException($violations);
        }

        /** @var Create\Command $command */
        $command = $this->denormalizer->denormalize($data, Create\Command::class);
        $orderId = $this->createHandler->handle($command);

        return new JsonResponse(JSendResponse::success([
            'order_id' => $orderId->value()
        ]), Response::HTTP_CREATED);
    }

    /**
     * @OA\Get(
     *     path="/orders",
     *     tags={"Orders"},
     *     description="Get all orders",
     *     @OA\Response(
     *         response=200,
     *         description="Success response",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", default="success"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="orders", type="array",
     *                      @OA\Items(
     *                          @OA\Property(property="id", type="integer"),
     *                          @OA\Property(property="status", type="string", enum={"new", "paid"}),
     *                          @OA\Property(property="items", type="array",
     *                              @OA\Items(
     *                                  @OA\Property(property="id", type="string", format="uuid"),
     *                                  @OA\Property(property="name", type="string"),
     *                                  @OA\Property(property="price", type="integer")
     *                              )
     *                          )
     *                      )
     *                 )
     *             )
     *         )
     *     )
     * )
     *
     * @return Response
     * @throws InvalidJSendException
     * @throws ExceptionInterface
     */
    public function getAll(): Response
    {
        return new JsonResponse(JSendResponse::success([
            'orders' => $this->orderFetcher->all()
        ]), Response::HTTP_OK);
    }

    /**
     * @OA\Post(
     *     path="/orders/{id}/pay",
     *     tags={"Orders"},
     *     description="Pay order by id",
     *     @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64",
     *         )
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="amount", type="integer")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success response",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", default="success"),
     *             @OA\Property(property="data", type="string", nullable=true, default="null")
     *         )
     *     )
     * )
     *
     * @param Request $request
     * @param int     $id
     *
     * @return Response
     * @throws InvalidJSendException
     * @throws ExceptionInterface
     */
    public function pay(Request $request, int $id): Response
    {
        $data = $request->request->all();

        $violations = $this->validator->validate($data, new Assert\Collection([
            'amount' => [
                new Assert\NotBlank(),
                new Assert\Type('int'),
                new Assert\Positive()
            ]
        ]));

        if ($violations->count() > 0) {
            throw new ValidationException($violations);
        }

        $data['order_id'] = (int) $id;

        /** @var Pay\Command $command */
        $command = $this->denormalizer->denormalize($data, Pay\Command::class);
        $this->payHandler->handle($command);

        return new JsonResponse(JSendResponse::success(), Response::HTTP_OK);
    }
}
