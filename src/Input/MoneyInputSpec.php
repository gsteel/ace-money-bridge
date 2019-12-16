<?php
declare(strict_types=1);

namespace ACE\Money\Input;

use ACE\Money\Filter\ToMoney;
use ACE\Money\Validator\MoneyValidator;

class MoneyInputSpec
{
    /** @return mixed[] */
    public function __invoke(bool $required = true) : array
    {
        return [
            'required' => $required,
            'filters' => [
                'toMoney' => [
                    'name' => ToMoney::class,
                ],
            ],
            'validators' => [
                'isCoords' => [
                    'name' => MoneyValidator::class,
                ],
            ],
        ];
    }
}
