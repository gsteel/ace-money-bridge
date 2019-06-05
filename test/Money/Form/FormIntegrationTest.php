<?php
declare(strict_types=1);

namespace ACETest\Money\Form;

use ACE\Money\Form\MoneyFieldset;
use ACETest\Money\BindableObject;
use ACETest\Money\TestCase;
use Money\Money;
use Zend\Form\Form;
use Zend\Hydrator\ClassMethods;

class FormIntegrationTest extends TestCase
{
    public function testProgrammaticFormCreation() : void
    {
        $container = $this->getContainer();
        $forms = $container->get('FormElementManager');

        /** @var Form $form */
        $form = $forms->get(Form::class);
        $form->setHydrator(new ClassMethods());
        $form->add([
            'name' => 'amount',
            'type' => MoneyFieldset::class,
        ]);
        $object = new BindableObject();
        $form->bind($object);
        $form->setData([
            'amount' => [
                'currency' => 'GBP',
                'amount' => 1,
            ],
        ]);
        $this->assertTrue($form->isValid());
        $money = $object->getAmount();
        $this->assertInstanceOf(Money::class, $money);
        $this->assertEquals(100, $money->getAmount());
    }
}
