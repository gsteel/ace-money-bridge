<?php
declare(strict_types=1);

namespace ACE\Money;

use Money as PHPMoney;

class ConfigProvider
{
    public function __invoke()
    {
        return [
            'defaultCurrencyCode' => 'GBP',
            'form_elements' => $this->formElementConfig(),
            'hydrators' => $this->hydratorConfig(),
            'dependencies' => $this->dependencyConfig(),
            'validators' => $this->validatorConfig(),
        ];
    }

    private function formElementConfig() : array
    {
        return [
            'factories' => [
                Form\MoneyFieldset::class => Container\MoneyFieldsetFactory::class,
                Form\Element\CurrencySelect::class => Container\CurrencySelectFactory::class,
            ],
        ];
    }

    private function hydratorConfig() : array
    {
        return [
            'factories' => [
                Hydrator\MoneyHydrator::class => Container\MoneyHydratorFactory::class,
            ],
        ];
    }

    private function dependencyConfig() : array
    {
        return [
            'factories' => [
                PHPMoney\Currencies::class => Container\DefaultCurrencyListFactory::class,
                PHPMoney\Currency::class => Container\DefaultCurrencyFactory::class,
            ],
        ];
    }

    private function validatorConfig() : array
    {
        return [
            'factories' => [
                Validator\CurrencyValidator::class => Container\CurrencyValidatorFactory::class,
            ],
        ];
    }
}
