<?php

declare(strict_types=1);

namespace App\Entity\Product;

use Webmozart\Assert\Assert;

class Id
{
    private int $value;

    /**
     * Id constructor.
     *
     * @param int $value
     */
    public function __construct(int $value)
    {
        Assert::greaterThan($value, 0);
        $this->value = $value;
    }

    /**
     * @return int
     */
    public function value(): int
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string) $this->value;
    }
}
