<?php
declare(strict_types=1);

namespace ACETest\Money\InputFilter;

use ACE\Money\Hydrator\MoneyHydrator;
use ACE\Money\InputFilter\MoneyInputFilter;
use ACE\Money\Validator\CurrencyValidator;
use ACETest\Money\TestCase;
use Zend\Hydrator\HydratorPluginManager;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterPluginManager;
use Zend\Validator\Regex;
use function json_encode;
use const JSON_PRETTY_PRINT;

class MoneyInputFilterTest extends TestCase
{
    /** @var MoneyInputFilter */
    private $filter;

    /** @var MoneyHydrator */
    private $hydrator;

    public function setUp() : void
    {
        parent::setUp();
        $container = $this->getContainer();
        $filters = $container->get(InputFilterPluginManager::class);
        $this->filter = $filters->get(MoneyInputFilter::class);
        $hydrators = $container->get(HydratorPluginManager::class);
        $this->hydrator = $hydrators->get(MoneyHydrator::class);
    }

    public function validData() : iterable
    {
        return [
            // Various valid number representations
            ['GBP', '1000.25', 100025],
            ['GBP', '0', 0],
            ['GBP', '0.00', 0],
            ['GBP', '0.01', 1],
            ['GBP', '100', 10000],
            ['GBP', '-0.01', -1],
            ['GBP', '-1', -100],
            // Currency Codes should be upper-cased and trimmed
            ['gbp', '1', 100],
            [' gbp ', '1', 100],
            // Numbers should be trimmed
            ['GBP', ' 1 ', 100],
            // Integers and floats should be acceptable
            ['GBP', 1, 100],
            ['GBP', 1.01, 101],
            ['GBP', -1, -100],
            ['GBP', -1.1, -110],
        ];
    }

    /**
     * @dataProvider validData
     */
    public function testValidValues(string $code, $amount, int $expectedAmount) : void
    {
        $this->filter->setData([
            'currency' => $code,
            'amount' => $amount,
        ]);
        $this->assertTrue($this->filter->isValid());
        $money = $this->hydrator->hydrate($this->filter->getValues(), null);
        $this->assertEquals($expectedAmount, $money->getAmount());
    }

    public function invalidValues() : iterable
    {
        return [
            ['ZZZ', '1', 'currency', CurrencyValidator::CODE_NOT_ACCEPTABLE],
            [['GBP'], '1', 'currency', CurrencyValidator::INVALID_TYPE],
            [1, '1', 'currency', CurrencyValidator::INVALID_CODE],
            ['ABCD', '1', 'currency', CurrencyValidator::INVALID_CODE],
            ['abcd', '1', 'currency', CurrencyValidator::INVALID_CODE],
            ['GBP', '1,000', 'amount', Regex::NOT_MATCH],
            ['GBP', '+1', 'amount', Regex::NOT_MATCH],
            ['GBP', '1,00', 'amount', Regex::NOT_MATCH],
        ];
    }

    /**
     * @dataProvider invalidValues
     */
    public function testInvalidValues($code, $amount, string $elementName, string $errorKey) : void
    {
        $this->filter->setData([
            'currency' => $code,
            'amount' => $amount,
        ]);
        $this->assertFalse($this->filter->isValid());
        $messages = $this->filter->getMessages();
        $this->assertArrayHasKey($elementName, $messages);
        $this->assertArrayHasKey($errorKey, $messages[$elementName], json_encode($messages, JSON_PRETTY_PRINT));
    }

    private function generateParentInputFilter() : InputFilter
    {
        $parentInputFilter = new InputFilter();
        $parentInputFilter->add([
            'name' => 'test',
            'required' => true,
        ]);
        $parentInputFilter->add($this->filter, 'money');
        return $parentInputFilter;
    }

    public function testNestedMoneyFilterIsRequiredByDefault() : void
    {
        $parentInputFilter = $this->generateParentInputFilter();
        $this->assertTrue($this->filter->isRequired());
        $parentInputFilter->setData([
            'test' => null,
            'money' => [
                'currency' => null,
                'amount' => null,
            ],
        ]);
        $this->assertFalse($parentInputFilter->isValid());
        $messages = $parentInputFilter->getMessages();
        $this->assertArrayHasKey('test', $messages);
        $this->assertArrayHasKey('money', $messages);
        $this->assertArrayHasKey('currency', $messages['money']);
        $this->assertArrayHasKey('amount', $messages['money']);
    }

    public function testNestedMoneyFilterCanBeOptional() : void
    {
        $parentInputFilter = $this->generateParentInputFilter();
        $this->filter->setRequired(false);
        $this->assertFalse($this->filter->isRequired());
        $parentInputFilter->setData([
            'test' => 'Foo',
            'money' => [
                'currency' => null,
                'amount' => null,
            ],
        ]);
        $this->assertTrue($parentInputFilter->isValid());
        $messages = $parentInputFilter->getMessages();
        $this->assertArrayNotHasKey('test', $messages);
        $this->assertArrayNotHasKey('money', $messages);
    }
}
