<?php
declare(strict_types=1);

namespace ACE\Money\Filter;

use Money\Currencies;
use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Parser\DecimalMoneyParser;
use Zend\Filter\AbstractFilter;
use function is_array;
use function is_numeric;

class ToMoney extends AbstractFilter
{
    /** @var Currencies */
    private $currencies;

    public function __construct(?Currencies $currencies = null)
    {
        if (! $currencies) {
            $currencies = new ISOCurrencies();
        }
        $this->currencies = $currencies;
    }

    /** @inheritDoc */
    public function filter($value)
    {
        if (! is_array($value)) {
            return $value;
        }
        if (! isset($value['currency'], $value['amount'])) {
            return $value;
        }
        $amount = $value['amount'];
        if (! is_numeric($amount)) {
            return $value;
        }
        $currency = (new CurrencyCodeToCurrencyFilter())->filter($value['currency']);
        if (! $currency instanceof Currency) {
            return $value;
        }
        $parser = new DecimalMoneyParser($this->currencies);
        return $parser->parse((string) $amount, $currency);
    }
}
