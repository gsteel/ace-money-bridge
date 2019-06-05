<?php
declare(strict_types=1);

namespace ACETest\Money\Container;

use ACE\Money\Container\DefaultCurrencyFactory;
use ACE\Money\Exception\ConfigurationException;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

class DefaultCurrencyFactoryTest extends TestCase
{
    public function testExceptionThrownWhenNoDefaultCodeIsAvailable() : void
    {
        $container = $this->prophesize(ContainerInterface::class);
        $container->get('config')->willReturn([]);
        $factory = new DefaultCurrencyFactory();
        $this->expectException(ConfigurationException::class);
        $this->expectExceptionMessage('Configuration should have a key "defaultCurrencyCode"');
        $factory($container->reveal());
    }

    public function testCurrencyIsReturnedWhenCodeIsAvailable() : void
    {
        $container = $this->prophesize(ContainerInterface::class);
        $container->get('config')->willReturn(['defaultCurrencyCode' => 'CAD']);
        $factory = new DefaultCurrencyFactory();
        $currency = $factory($container->reveal());
        $this->assertEquals('CAD', $currency->getCode());
    }
}
