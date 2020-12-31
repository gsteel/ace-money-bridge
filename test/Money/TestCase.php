<?php

declare(strict_types=1);

namespace ACETest\Money;

use ACE;
use Laminas;
use Laminas\ConfigAggregator\ConfigAggregator;
use Laminas\ServiceManager\ServiceManager;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use Psr\Container\ContainerInterface;

class TestCase extends PHPUnitTestCase
{
    /** @return mixed[] */
    protected function defaultConfiguration(): array
    {
        $aggregator = new ConfigAggregator([
            Laminas\I18n\ConfigProvider::class,
            Laminas\Form\ConfigProvider::class,
            Laminas\InputFilter\ConfigProvider::class,
            Laminas\Filter\ConfigProvider::class,
            Laminas\Hydrator\ConfigProvider::class,
            Laminas\Validator\ConfigProvider::class,
            ACE\Money\ConfigProvider::class,
        ]);

        return $aggregator->getMergedConfig();
    }

    protected function getContainer(): ContainerInterface
    {
        $config = $this->defaultConfiguration();
        $dependencies = $config['dependencies'];
        $dependencies['services']['config'] = $config;

        return new ServiceManager($dependencies);
    }
}
