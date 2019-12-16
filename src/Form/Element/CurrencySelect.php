<?php
declare(strict_types=1);

namespace ACE\Money\Form\Element;

use ACE\Money\Validator\CurrencyValidator;
use Money\Currencies;
use Money\Currency;
use Zend\Filter;
use Zend\Form\Element\Select;

class CurrencySelect extends Select
{
    public function __construct(Currencies $currencies)
    {
        parent::__construct();
        $this->setValueOptions($this->valueOptionsFromCurrencyList($currencies));
    }

    /** @inheritDoc */
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

    /** @return string[] */
    private function valueOptionsFromCurrencyList(Currencies $currencies) : array
    {
        $options = [];
        /** @var Currency $item */
        foreach ($currencies->getIterator() as $item) {
            $code = $item->getCode();
            $options[$code] = $code;
        }
        return $options;
    }
}
