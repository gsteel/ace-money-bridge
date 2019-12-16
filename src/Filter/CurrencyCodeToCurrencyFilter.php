<?php
declare(strict_types=1);

namespace ACE\Money\Filter;

use Money\Currency;
use Zend\Filter\FilterInterface;
use function is_string;
use function preg_match;
use function strtoupper;
use function trim;

class CurrencyCodeToCurrencyFilter implements FilterInterface
{
    /** @inheritDoc */
    public function filter($value)
    {
        if ($value instanceof Currency) {
            return $value;
        }
        if (! is_string($value)) {
            return $value;
        }
        $code = strtoupper(trim($value));
        if (! preg_match('/^[A-Z]{3}$/', $code)) {
            return $value;
        }
        return new Currency($code);
    }
}
