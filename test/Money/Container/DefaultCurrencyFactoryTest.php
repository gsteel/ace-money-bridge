<?php

declare(strict_types=1);

namespace ACETest\Money\Container;

use ACE\Money\Container\DefaultCurrencyFactory;
use ACE\Money\Exception\ConfigurationException;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

class DefaultCurrencyFactoryTest extends TestCase
{
    public function testExceptionThrownWhenNoDefaultCodeIsAvailable(): void
    {
        $container = $this->createMock(ContainerInterface::class);
        $container->expects(self::once())
            ->method('get')
            ->with('config')
            ->willReturn([]);
        $factory = new DefaultCurrencyFactory();
        $this->expectException(ConfigurationException::class);
        $this->expectExceptionMessage('Configuration should have a key "defaultCurrencyCode"');
        $factory($container);
    }

    public function testCurrencyIsReturnedWhenCodeIsAvailable(): void
    {
        $container = $this->createMock(ContainerInterface::class);
        $container->expects(self::once())
            ->method('get')
            ->with('config')
            ->willReturn(['defaultCurrencyCode' => 'CAD']);
        $factory = new DefaultCurrencyFactory();
        $currency = $factory($container);
        self::assertEquals('CAD', $currency->getCode());
    }
}
