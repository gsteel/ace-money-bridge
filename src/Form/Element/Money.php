<?php
declare(strict_types=1);

namespace ACE\Money\Form\Element;

use Zend\Filter;
use Zend\Form\Element\Number as NumberElement;
use Zend\InputFilter\InputProviderInterface;
use Zend\Validator\Regex;

class Money extends NumberElement
{
    /** @inheritDoc */
    public function getInputSpecification() : array
    {
        return [
            'name' => $this->getName(),
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
        ];
    }
}
