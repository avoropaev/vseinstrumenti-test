<?php

declare(strict_types=1);

namespace App\Entity\Order;

use Webmozart\Assert\Assert;

class Status
{
    public const NEW = 'new';
    public const PAID = 'paid';

    private string $name;

    /**
     * Status constructor.
     *
     * @param string $name
     */
    public function __construct(string $name)
    {
        Assert::oneOf($name, [
            self::NEW,
            self::PAID
        ]);

        $this->name = $name;
    }

    /**
     * @return static
     */
    public static function new(): self
    {
        return new self(self::NEW);
    }

    /**
     * @return static
     */
    public static function paid(): self
    {
        return new self(self::PAID);
    }

    /**
     * @param Status $other
     *
     * @return bool
     */
    public function isEqual(self $other): bool
    {
        return $this->name() === $other->name();
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * @return bool
     */
    public function isNew(): bool
    {
        return $this->name === self::NEW;
    }

    /**
     * @return bool
     */
    public function isPaid(): bool
    {
        return $this->name === self::PAID;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->name;
    }
}
