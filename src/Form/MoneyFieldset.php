<?php
declare(strict_types=1);

namespace ACE\Money\Form;

use ACE\Money\Hydrator\MoneyHydrator;
use Money\Currency;
use Money\Money;
use Zend\Form\ElementInterface;
use Zend\Form\Fieldset;
use function is_iterable;

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
    }

    public function setOptions($options) : self
    {
        parent::setOptions($options);
        $currencyOptions = $this->getOption('currency');
        $currencyOptions = $currencyOptions['options'] ?? null;
        if (is_iterable($currencyOptions)) {
            $this->currencyElement()->setOptions($currencyOptions);
        }
        $amountOptions = $this->getOption('amount');
        $amountOptions = $amountOptions['options'] ?? null;
        if (is_iterable($amountOptions)) {
            $this->amountElement()->setOptions($amountOptions);
        }
        return $this;
    }

    public function currencyElement() : ElementInterface
    {
        return $this->get('currency');
    }

    public function amountElement() : ElementInterface
    {
        return $this->get('amount');
    }
}
