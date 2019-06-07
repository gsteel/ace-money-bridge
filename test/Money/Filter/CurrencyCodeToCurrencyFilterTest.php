<?php
declare(strict_types=1);

namespace ACETest\Money\Filter;

use ACE\Money\Filter\CurrencyCodeToCurrencyFilter;
use Money\Currency;
use PHPUnit\Framework\TestCase;
use stdClass;

class CurrencyCodeToCurrencyFilterTest extends TestCase
{

    /** @var CurrencyCodeToCurrencyFilter */
    private $filter;

    protected function setUp() : void
    {
        parent::setUp();
        $this->filter = new CurrencyCodeToCurrencyFilter();
    }

    public function dataThatShouldNotBeFiltered() : array
    {
        return [
            [''], [null], [0], [0.0], [[]], [new stdClass()],
            ['a'], ['aa'], ['aaaa'], ['111'], [111], ['A12'], ['AA1'],
        ];
    }

    /**
     * @dataProvider dataThatShouldNotBeFiltered
     */
    public function testUnfilteredValues($value) : void
    {
        $filtered = $this->filter->filter($value);
        $this->assertSame($value, $filtered);
    }

    public function dataThatShouldBeFiltered() : array
    {
        return [
            ['GBP', 'GBP'],
            [' gbp ', 'GBP'],
            ['AAA', 'AAA'],
            ['ZZZ', 'ZZZ'],
        ];
    }

    /**
     * @dataProvider dataThatShouldBeFiltered
     */
    public function testFilteredValues($value, $expect) : void
    {
        $filtered = $this->filter->filter($value);
        $this->assertInstanceOf(Currency::class, $filtered);
        $this->assertSame($expect, $filtered->getCode());
    }

    public function testCurrencyInstancesAreReturnedAsIs() : void
    {
        $currency = new Currency('GBP');
        $filtered = $this->filter->filter($currency);
        $this->assertSame($currency, $filtered);
    }
}
