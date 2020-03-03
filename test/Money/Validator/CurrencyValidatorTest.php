<?php
declare(strict_types=1);

namespace ACETest\Money\Validator;

use ACE\Money\Validator\CurrencyValidator;
use Money\Currencies\ISOCurrencies;
use Money\Currency;
use PHPUnit\Framework\TestCase;

class CurrencyValidatorTest extends TestCase
{
    /**
     * @var CurrencyValidator
     */
    private $validator;

    protected function setUp() : void
    {
        parent::setUp();
        $this->validator = new CurrencyValidator(new ISOCurrencies());
    }

    public function testNonString() : void
    {
        $this->assertFalse($this->validator->isValid(1));
        $this->assertArrayHasKey(CurrencyValidator::INVALID_TYPE, $this->validator->getMessages());
    }

    public function invalidStringCodes() : array
    {
        return [
            ['a'], // Too Short
            ['aaaa'], // Too Long
            ['aaa'], // Lowercase
            ['!!!'], // Not Alpha
            ['123'], // Not alpha
        ];
    }

    /**
     * @dataProvider invalidStringCodes
     */
    public function testInvalidCode(string $code) : void
    {
        $this->assertFalse($this->validator->isValid($code));
        $this->assertArrayHasKey(CurrencyValidator::INVALID_CODE, $this->validator->getMessages());
    }

    public function testCurrencyInstanceIsValid() : void
    {
        $this->assertTrue($this->validator->isValid(new Currency('GBP')));
    }

    public function testCurrencyInstanceMustBeInAllowableList() : void
    {
        $this->assertFalse($this->validator->isValid(new Currency('ZZZ')));
        $this->assertArrayHasKey(CurrencyValidator::CODE_NOT_ACCEPTABLE, $this->validator->getMessages());
        $this->assertStringContainsString(
            'The currency "ZZZ" is not available',
            $this->validator->getMessages()[CurrencyValidator::CODE_NOT_ACCEPTABLE]
        );
    }

    public function validCodes() : iterable
    {
        /** @var Currency $currency */
        foreach ((new ISOCurrencies())->getIterator() as $currency) {
            yield [$currency->getCode()];
        }
    }

    /**
     * @dataProvider validCodes
     */
    public function testValidCodes(string $code) : void
    {
        $this->assertTrue($this->validator->isValid($code));
    }

    public function testCodeNotInListIsInvalid() : void
    {
        $this->assertFalse($this->validator->isValid('ZZZ'));
        $this->assertArrayHasKey(CurrencyValidator::CODE_NOT_ACCEPTABLE, $this->validator->getMessages());
        $this->assertStringContainsString(
            'The currency "ZZZ" is not available',
            $this->validator->getMessages()[CurrencyValidator::CODE_NOT_ACCEPTABLE]
        );
    }
}
