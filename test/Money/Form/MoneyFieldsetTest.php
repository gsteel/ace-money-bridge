<?php
declare(strict_types=1);

namespace ACETest\Money\Form;

use ACE\Money\Form\MoneyFieldset;
use ACE\Money\Hydrator\MoneyHydrator;
use ACETest\Money\BindableObject;
use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Formatter\DecimalMoneyFormatter;
use Money\Money;
use Money\Parser\DecimalMoneyParser;
use PHPUnit\Framework\TestCase;
use Zend\Form\Element\Text;
use Zend\Form\Form;
use Zend\Hydrator\ClassMethods;

class MoneyFieldsetTest extends TestCase
{

    /**
     * @var MoneyHydrator
     */
    private $hydrator;

    protected function setUp() : void
    {
        parent::setUp();
        $list = new ISOCurrencies();
        $this->hydrator = new MoneyHydrator(
            new DecimalMoneyFormatter($list),
            new DecimalMoneyParser($list)
        );
    }

    public function testConstructorAddsFormElements() : void
    {
        $currency = new Text();
        $amount = new Text();
        $fieldset = new MoneyFieldset($currency, $amount, $this->hydrator, new Currency('GBP'));
        $this->assertSame('currency', $currency->getName());
        $this->assertSame('amount', $amount->getName());
        $this->assertContains($currency, $fieldset->getElements());
        $this->assertContains($amount, $fieldset->getElements());
    }

    private function fieldset() : MoneyFieldset
    {
        $currency = new Text();
        $amount = new Text();
        return new MoneyFieldset($currency, $amount, $this->hydrator, new Currency('GBP'));
    }

    private function form() : Form
    {
        $form = new Form();
        $fieldset = $this->fieldset();
        $fieldset->setName('amount');
        $form->add($fieldset);
        return $form;
    }

    public function testFieldsetBinding() : void
    {
        $form = $this->form();
        $form->setHydrator(new ClassMethods());
        $bind = new BindableObject();
        $money = new Money(2000, new Currency('GBP'));
        $bind->setAmount($money);
        $form->bind($bind);
        $form->isValid();
        $value = $form->getData();
        $this->assertObjectHasAttribute('amount', $value);
        $this->assertInstanceOf(Money::class, $value->amount);
        /** @var Money $moneyProperty */
        $moneyProperty = $value->amount;
        $this->assertNotSame($money, $moneyProperty);
        $this->assertEquals('GBP', $moneyProperty->getCurrency()->getCode());
        $this->assertEquals(2000, $moneyProperty->getAmount());
    }

    public function testValidatedFormValuesAreReflectedInObject() : void
    {
        $form = $this->form();
        $form->setHydrator(new ClassMethods());
        $bind = new BindableObject();
        $form->bind($bind);
        $form->setData([
            'amount' => [
                'currency' => 'ZAR',
                'amount' => '123.45',
            ],
        ]);
        $this->assertTrue($form->isValid());
        $object = $form->getData();
        $this->assertInstanceOf(Money::class, $object->amount);
        /** @var Money $moneyProperty */
        $moneyProperty = $object->amount;
        $this->assertEquals('ZAR', $moneyProperty->getCurrency()->getCode());
        $this->assertEquals(12345, $moneyProperty->getAmount());
    }
}
