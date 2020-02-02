<?php

declare(strict_types=1);

namespace App\Exception;

use InvalidArgumentException;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Throwable;

class ValidationException extends InvalidArgumentException
{
    private ConstraintViolationListInterface $violationList;

    /**
     * @inheritDoc
     */
    public function __construct(ConstraintViolationListInterface $violationList, Throwable $previous = null)
    {
        parent::__construct('Bad request.', 400, $previous);

        $this->violationList = $violationList;
    }

    /**
     * @return ConstraintViolationListInterface
     */
    public function violationList(): ConstraintViolationListInterface
    {
        return $this->violationList;
    }
}
