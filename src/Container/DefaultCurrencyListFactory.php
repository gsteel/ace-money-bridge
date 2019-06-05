<?php
declare(strict_types=1);

namespace ACE\Money\Container;

use Money\Currencies;
use Psr\Container\ContainerInterface;

class DefaultCurrencyListFactory
{
    public function __invoke(ContainerInterface $container) : Currencies
    {
        return new Currencies\ISOCurrencies();
    }
}
