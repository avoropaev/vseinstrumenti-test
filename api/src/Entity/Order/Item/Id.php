<?php

declare(strict_types=1);

namespace App\Entity\Order\Item;

use Exception;
use Ramsey\Uuid\Uuid;
use RuntimeException;
use Webmozart\Assert\Assert;

class Id
{
    private string $value;

    /**
     * Id constructor.
     *
     * @param string $value
     */
    public function __construct(string $value)
    {
        Assert::notEmpty($value);
        Assert::uuid($value);
        $this->value = $value;
    }

    /**
     * @return static
     */
    public static function next(): self
    {
        try {
            $value = Uuid::uuid4()->toString();
        } catch (Exception $e) {
            throw new RuntimeException($e->getMessage(), (int) $e->getCode(), $e);
        }

        return new self($value);
    }

    /**
     * @param Id $other
     *
     * @return bool
     */
    public function isEqual(self $other): bool
    {
        return $this->value() === $other->value();
    }

    /**
     * @return string
     */
    public function value(): string
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->value;
    }
}
