<?php
declare(strict_types=1);

namespace ACETest\Money\Form\Element;

use ACE\Money\Form\Element\MoneyElement;
use ACETest\Money\TestCase;
use Money\Currency;
use Money\Money as MoneyValue;
use Zend\Form\Form;
use function json_encode;

class MoneyElementTest extends TestCase
{
    /** @var MoneyElement */
    private $element;

    /** @var Form */
    private $form;

    protected function setUp() : void
    {
        parent::setUp();
        $container = $this->getContainer();
        $forms = $container->get('FormElementManager');
        $this->element = $forms->get(MoneyElement::class);
    }

    private function prepareForm() : Form
    {
        $container = $this->getContainer();
        $forms = $container->get('FormElementManager');
        $this->form = $forms->get(Form::class);
        $this->element->setName('myMoney');
        $this->form->add($this->element);
        $this->form->prepare();
        return $this->form;
    }

    public function testElementsCanBeRetrieved() : void
    {
        $this->assertNotNull($this->element->amountElement());
        $this->assertNotNull($this->element->currencyElement());
    }

    public function testWeCanGetSomeMoneyOutOfTheForm() : void
    {
        $this->prepareForm();
        $this->assertSame('myMoney', $this->element->getName());
        $this->assertSame($this->element, $this->form->get('myMoney'));

        $input = [
            'myMoney' => [
                'amount' => '1.23',
                'currency' => 'GBP',
            ],
        ];

        $this->form->setData($input);
        $this->assertTrue($this->form->isValid(), json_encode($this->form->getMessages()));
        $output = $this->form->getData();
        $this->assertArrayHasKey('myMoney', $output);
        $this->assertInstanceOf(MoneyValue::class, $output['myMoney']);
    }

    public function testMoneyElementInputIsOk() : void
    {
        $this->prepareForm();
        $money = new MoneyValue(123, new Currency('GBP'));
        $this->form->setData(['myMoney' => $money]);
        $this->assertTrue($this->form->isValid());
        $out = $this->form->getData()['myMoney'];
        $this->assertInstanceOf(MoneyValue::class, $out);
        $this->assertTrue($money->equals($out));
    }

    public function testInvalidArrayInput() : void
    {
        $input = [
            'myMoney' => [
                'baz' => 'bat',
                'bing' => 'bong',
            ],
        ];
        $this->prepareForm();
        $this->form->setData($input);
        $this->assertFalse($this->form->isValid());
        $out = $this->form->getData()['myMoney'];
        $this->assertEquals($input['myMoney'], $out);
    }

    public function testStringValueCanBeRetrieved() : void
    {
        $input = [
            'myMoney' => [
                'amount' => '1.12',
                'currency' => 'GBP',
            ],
        ];
        $this->prepareForm();
        $this->form->setData($input);
        $value = $this->element->getValue();
        $this->assertStringMatchesFormat('%s %f', $value);
    }
}
