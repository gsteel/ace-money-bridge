<?php
declare(strict_types=1);

namespace ACETest\Money\Filter;

use ACE\Money\Filter\ToMoney;
use Money\Currency;
use Money\Money;
use PHPUnit\Framework\TestCase;

class ToMoneyTest extends TestCase
{
    /** @return mixed[] */
    public function validValues() : array
    {
        return [
            ['1.00', 'GBP', 100, 'GBP'],
            [1.10, 'GBP', 110, 'GBP'],
            ['123', 'GBP', 12300, 'GBP'],
            ['0', 'GBP', 0, 'GBP'],
            ['0.0', 'GBP', 0, 'GBP'],
            [0, 'GBP', 0, 'GBP'],
            [0.0, 'GBP', 0, 'GBP'],
            ['-1.00', 'GBP', -100, 'GBP'],
            [-1, 'GBP', -100, 'GBP'],
        ];
    }

    /** @return mixed[] */
    public function invalidInput() : array
    {
        return [
            [[]],
            ['foo'],
            [1],
            [null],
            [1.9],
            [['currency' => '', 'amount' => '']],
            [['currency' => 'GBP', 'amount' => '']],
            [['currency' => 'GBP', 'amount' => null]],
            [['currency' => '123', 'amount' => 'GBP']],
            [['currency' => '123', 'amount' => 'GBP']],
            [['currency' => 'GB', 'amount' => '123']],
        ];
    }

    /**
     * @param mixed $inputAmount
     * @param mixed $inputCode
     *
     * @dataProvider validValues
     */
    public function testValidValues($inputAmount, $inputCode, int $expectAmount, string $expectCode) : void
    {
        $input = [
            'currency' => $inputCode,
            'amount' => $inputAmount,
        ];
        $result = (new ToMoney())->filter($input);
        $this->assertInstanceOf(Money::class, $result);
        $this->assertSame($expectAmount, (int) $result->getAmount());
        $this->assertSame($expectCode, $result->getCurrency()->getCode());
    }

    /**
     * @param mixed $input
     *
     * @dataProvider invalidInput
     */
    public function testInvalidInput($input) : void
    {
        $result = (new ToMoney())->filter($input);
        $this->assertEquals($input, $result);
    }

    public function testMoneyInstanceWillNotBeFiltered() : void
    {
        $input = new Money(100, new Currency('GBP'));
        $result = (new ToMoney())->filter($input);
        $this->assertSame($input, $result);
    }
}
