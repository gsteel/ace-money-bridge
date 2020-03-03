<?php
declare(strict_types=1);

namespace ACETest\Money\Form;

use ACE\Money\Form\MoneyFieldset;
use ACETest\Money\BindableObject;
use ACETest\Money\TestCase;
use Laminas\Form\Form;
use Laminas\Hydrator\ClassMethodsHydrator;
use Money\Money;

class FormIntegrationTest extends TestCase
{
    public function testProgrammaticFormCreation() : void
    {
        $container = $this->getContainer();
        $forms = $container->get('FormElementManager');

        /** @var Form $form */
        $form = $forms->get(Form::class);
        $form->setHydrator(new ClassMethodsHydrator());
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

    public function testElementOptionsAndAttributesAreProvidedToIndividualElements() : void
    {
        $container = $this->getContainer();
        $forms = $container->get('FormElementManager');

        /** @var Form $form */
        $form = $forms->get(Form::class);
        $form->add([
            'name' => 'money',
            'type' => MoneyFieldset::class,
            'options' => [
                'currency' => [
                    'options' => [
                        'label' => 'Currency Label',
                    ],
                    'attributes' => [
                        'class' => 'c',
                    ],
                ],
                'amount' => [
                    'options' => [
                        'label' => 'Amount Label',
                    ],
                    'attributes' => [
                        'class' => 'a',
                    ],
                ],
            ],
        ]);
        /** @var MoneyFieldset $fieldset */
        $fieldset = $form->get('money');
        $this->assertInstanceOf(MoneyFieldset::class, $fieldset);
        $this->assertSame('Currency Label', $fieldset->currencyElement()->getLabel());
        $this->assertSame('Amount Label', $fieldset->amountElement()->getLabel());
        $this->assertSame('c', $fieldset->currencyElement()->getAttribute('class'));
        $this->assertSame('a', $fieldset->amountElement()->getAttribute('class'));
    }
}
