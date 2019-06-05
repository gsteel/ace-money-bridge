<?php
declare(strict_types=1);

namespace ACETest\Money\Container;

use ACE\Money\Form\MoneyFieldset;
use ACETest\Money\TestCase;

class MoneyFieldsetFactoryTest extends TestCase
{
    public function testFieldsetCanBeRetrievedFromFormManager() : void
    {
        $container = $this->getContainer();
        $forms = $container->get('FormElementManager');
        $fieldset = $forms->get(MoneyFieldset::class);
        $this->assertInstanceOf(MoneyFieldset::class, $fieldset);
    }
}
