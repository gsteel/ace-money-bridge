<?php
declare(strict_types=1);

namespace ACE\Money\Form\Element;

use ACE\Money\Form\Element\Currency as CurrencyInput;
use ACE\Money\Form\Element\Money as MoneyInput;
use ACE\Money\Hydrator\MoneyHydrator;
use ACE\Money\Input\MoneyInputSpec;
use Money\Money;
use Zend\Form\Element;
use Zend\Form\ElementPrepareAwareInterface;
use Zend\Form\FormInterface;
use Zend\InputFilter\InputProviderInterface;
use function is_array;
use function sprintf;
use function trim;

class MoneyElement extends Element implements InputProviderInterface, ElementPrepareAwareInterface
{
    /** @var MoneyInput */
    private $amount;

    /** @var CurrencyInput */
    private $currency;

    /** @var MoneyHydrator|null */
    private $hydrator;

    /** @inheritDoc */
    public function __construct(MoneyHydrator $hydrator)
    {
        $this->amount = new MoneyInput();
        $this->currency = new CurrencyInput();
        $this->hydrator = $hydrator;
        parent::__construct();
    }

    /** @inheritDoc */
    public function setValue($value)
    {
        if ($value instanceof Money) {
            $value = $this->hydrator->extract($value);
        }
        if (is_array($value) && isset($value['currency'], $value['amount'])) {
            $this->currency->setValue($value['currency']);
            $this->amount->setValue($value['amount']);
        }
        return $this;
    }

    /** @inheritDoc */
    public function getValue()
    {
        return trim(sprintf(
            '%s %s',
            $this->currency->getValue(),
            $this->amount->getValue()
        ));
    }

    /**
     * @inheritDoc
     */
    public function prepareElement(FormInterface $form) : void
    {
        $name = $this->getName();
        $this->amount->setName($name . '[amount]');
        $this->currency->setName($name . '[currency]');
    }

    /**
     * @inheritDoc
     */
    public function getInputSpecification() : array
    {
        $spec = (new MoneyInputSpec())($this->hasAttribute('required'));
        $spec['name'] = $this->getName();
        return $spec;
    }

    public function currencyElement() : CurrencyInput
    {
        return $this->currency;
    }

    public function amountElement() : MoneyInput
    {
        return $this->amount;
    }
}
