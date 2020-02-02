<?php

declare(strict_types=1);

namespace App\Service\UseCase\Order\Create;

class Command
{
    /** @var array|int[] */
    public array $productsIds = [];
}
