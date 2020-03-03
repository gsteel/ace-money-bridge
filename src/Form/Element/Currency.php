<?php
declare(strict_types=1);

namespace ACE\Money\Form\Element;

use ACE\Money\Validator\CurrencyValidator;
use Laminas\Filter;
use Laminas\Form\Element\Text as TextElement;
use Laminas\InputFilter\InputProviderInterface;

class Currency extends TextElement implements InputProviderInterface
{
    protected $attributes = [
        'type' => 'text',
        'maxlength' => 3,
    ];

    public function getInputSpecification() : array
    {
        return [
            'name' => $this->getName(),
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
        ];
    }
}
