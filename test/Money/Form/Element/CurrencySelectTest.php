<?php
declare(strict_types=1);

namespace ACETest\Money\Form\Element;

use ACE\Money\Form\Element\CurrencySelect;
use ACETest\Money\TestCase;
use Laminas\ServiceManager\AbstractPluginManager;
use Money\Currencies\ISOCurrencies;

class CurrencySelectTest extends TestCase
{
    public function testSelectContainsExpectedOptions() : void
    {
        $select = new CurrencySelect(new ISOCurrencies());
        $this->assertContains('GBP', $select->getValueOptions());
    }

    public function testElementCanBeRetrievedFromFormManager() : void
    {
        /** @var AbstractPluginManager $forms */
        $forms = $this->getContainer()->get('FormElementManager');
        $element = $forms->get(CurrencySelect::class);
        $this->assertInstanceOf(CurrencySelect::class, $element);
    }
}
