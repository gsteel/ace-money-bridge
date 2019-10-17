<?php
declare(strict_types=1);

namespace ACE\Money\Form;

use ACE\Money\Hydrator\MoneyHydrator;
use Money\Currency;
use Money\Money;
use Zend\Form\ElementInterface;
use Zend\Form\Fieldset;

class MoneyFieldset extends Fieldset
{
    public function __construct(
        ElementInterface $currencyElement,
        ElementInterface $amountElement,
        MoneyHydrator $hydrator,
        Currency $defaultCurrency
    ) {
        parent::__construct();
        $currencyElement->setName('currency');
        $this->add($currencyElement);
        $amountElement->setName('amount');
        $this->add($amountElement);
        $this->setHydrator($hydrator);
        $this->setAllowedObjectBindingClass(Money::class);
        // If an initial object is not set, hydration will not work and an array will be returned in getData()
        $this->setObject(
            new Money(0, $defaultCurrency)
        );
        $this->populateValues([
            'currency' => $defaultCurrency->getCode(),
        ]);
    }
}
