<?php
declare(strict_types=1);

namespace ACE\Money\InputFilter;

use ACE\Money\Validator\CurrencyValidator;
use Zend\Filter;
use Zend\InputFilter\InputFilter;
use Zend\Validator\Regex;

class MoneyInputFilter extends InputFilter
{
    public function init() : void
    {
        parent::init();
        $this->add([
            'name' => 'currency',
            'required' => true,
            'validators' => [
                'currency' => [
                    'name' => CurrencyValidator::class,
                ],
            ],
            'filters' => [
                'trim' => [
                    'name' => Filter\StringTrim::class,
                ],
                'upper' => [
                    'name' => Filter\StringToUpper::class,
                ],
            ],
        ]);
        $this->add([
            'name' => 'amount',
            'required' => true,
            'validators' => [
                'float' => [
                    'name' => Regex::class,
                    'options' => [
                        'pattern' => '(^-?\d*(\.\d+)?$)',
                        'messages' => [
                            Regex::NOT_MATCH => 'Monetary values should be positive or negative floating point ' .
                                'numbers with or without a decimal point and should not include thousand separators',
                        ],
                    ],
                ],
            ],
            'filters' => [
                'trim' => [
                    'name' => Filter\StringTrim::class,
                ],
                'toString' => [
                    'name' => Filter\Callback::class,
                    'options' => [
                        'callback' => static function ($value) : string {
                            return (string) $value;
                        },
                    ],
                ],
            ],
        ]);
    }
}
