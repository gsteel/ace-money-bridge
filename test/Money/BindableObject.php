<?php

declare(strict_types=1);

namespace ACETest\Money;

use Money\Money;

class BindableObject
{
    /** @var Money|null */
    public $amount;

    public function setAmount(Money $money): void
    {
        $this->amount = $money;
    }

    public function getAmount(): ?Money
    {
        return $this->amount;
    }
}
