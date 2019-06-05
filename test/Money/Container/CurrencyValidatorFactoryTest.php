<?php
declare(strict_types=1);

namespace ACETest\Money\Container;

use ACE\Money\Container\CurrencyValidatorFactory;
use Money\Currencies;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

class CurrencyValidatorFactoryTest extends TestCase
{
    public function testFactory() : void
    {
        $container = $this->prophesize(ContainerInterface::class);
        $container->get(Currencies::class)->willReturn(new Currencies\ISOCurrencies());
        $factory = new CurrencyValidatorFactory();
        $factory($container->reveal());
        $this->addToAssertionCount(1);
    }
}
