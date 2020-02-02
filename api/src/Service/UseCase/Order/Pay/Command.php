<?php

declare(strict_types=1);

namespace App\Service\UseCase\Order\Pay;

class Command
{
    public ?int $orderId = null;
    public ?int $amount = null;
}
