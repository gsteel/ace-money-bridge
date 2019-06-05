<?php
declare(strict_types=1);

namespace ACETest\Money\Container;

use ACE\Money\Container\DefaultCurrencyListFactory;
use Money\Currencies\ISOCurrencies;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

class DefaultCurrencyListFactoryTest extends TestCase
{
    public function testFactoryReturnsISOCurrenciesByDefault() : void
    {
        $container = $this->prophesize(ContainerInterface::class)->reveal();
        $factory = new DefaultCurrencyListFactory();
        $list = $factory($container);
        $this->assertInstanceOf(ISOCurrencies::class, $list);
    }
}
