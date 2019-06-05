<?php
declare(strict_types=1);

namespace ACE\Money\Container;

use ACE\Money\Validator\CurrencyValidator;
use Money\Currencies;
use Psr\Container\ContainerInterface;

class CurrencyValidatorFactory
{
    public function __invoke(ContainerInterface $container) : CurrencyValidator
    {
        return new CurrencyValidator($container->get(Currencies::class));
    }
}
