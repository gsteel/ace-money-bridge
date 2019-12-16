<?php
declare(strict_types=1);

namespace ACE\Money;

use Money as PHPMoney;

class ConfigProvider
{
    /** @return mixed[] */
    public function __invoke() : array
    {
        return [
            'defaultCurrencyCode' => 'GBP',
            'form_elements' => $this->formElementConfig(),
            'hydrators' => $this->hydratorConfig(),
            'dependencies' => $this->dependencyConfig(),
            'validators' => $this->validatorConfig(),
        ];
    }

    /** @return mixed[] */
    private function formElementConfig() : array
    {
        return [
            'factories' => [
                Form\MoneyFieldset::class => Container\MoneyFieldsetFactory::class,
                Form\Element\CurrencySelect::class => Container\CurrencySelectFactory::class,
                Form\Element\MoneyElement::class => Container\MoneyElementFactory::class,
            ],
        ];
    }

    /** @return mixed[] */
    private function hydratorConfig() : array
    {
        return [
            'factories' => [
                Hydrator\MoneyHydrator::class => Container\MoneyHydratorFactory::class,
            ],
        ];
    }

    /** @return mixed[] */
    private function dependencyConfig() : array
    {
        return [
            'factories' => [
                PHPMoney\Currencies::class => Container\DefaultCurrencyListFactory::class,
                PHPMoney\Currency::class => Container\DefaultCurrencyFactory::class,
            ],
        ];
    }

    /** @return mixed[] */
    private function validatorConfig() : array
    {
        return [
            'factories' => [
                Validator\CurrencyValidator::class => Container\CurrencyValidatorFactory::class,
                Validator\MoneyValidator::class => Container\MoneyValidatorFactory::class,
            ],
        ];
    }
}
