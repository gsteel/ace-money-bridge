<?php
declare(strict_types=1);

namespace ACE\Money\Container;

use ACE\Money\Form\Element\Currency as CurrencyElement;
use ACE\Money\Form\Element\Money;
use ACE\Money\Form\MoneyFieldset;
use ACE\Money\Hydrator\MoneyHydrator;
use Money\Currency;
use Psr\Container\ContainerInterface;
use Zend\Form\FormElementManager\FormElementManagerV3Polyfill;
use Zend\Hydrator\HydratorPluginManager;

class MoneyFieldsetFactory
{
    public function __invoke(ContainerInterface $container) : MoneyFieldset
    {
        /** @var FormElementManagerV3Polyfill $formManager */
        $formManager = $container->get('FormElementManager');
        $hydrators = $container->get(HydratorPluginManager::class);
        return new MoneyFieldset(
            $formManager->get(CurrencyElement::class),
            $formManager->get(Money::class),
            $hydrators->get(MoneyHydrator::class),
            $container->get(Currency::class)
        );
    }
}
