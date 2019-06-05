<?php
declare(strict_types=1);

namespace ACETest\Money;

use ACE;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use Psr\Container\ContainerInterface;
use Zend;
use Zend\ConfigAggregator\ConfigAggregator;
use Zend\ServiceManager\ServiceManager;

class TestCase extends PHPUnitTestCase
{

    protected function defaultConfiguration() : array
    {
        $aggregator = new ConfigAggregator([
            Zend\I18n\ConfigProvider::class,
            Zend\Form\ConfigProvider::class,
            Zend\InputFilter\ConfigProvider::class,
            Zend\Filter\ConfigProvider::class,
            Zend\Hydrator\ConfigProvider::class,
            Zend\Validator\ConfigProvider::class,
            ACE\Money\ConfigProvider::class,
        ]);
        return $aggregator->getMergedConfig();
    }

    protected function getContainer() : ContainerInterface
    {
        $config = $this->defaultConfiguration();
        $dependencies = $config['dependencies'];
        $dependencies['services']['config'] = $config;
        return new ServiceManager($dependencies);
    }
}
