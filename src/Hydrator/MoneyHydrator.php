<?php
declare(strict_types=1);

namespace ACE\Money\Hydrator;

use ACE\Money\Exception;
use Money\Money;
use Money\MoneyFormatter;
use Money\MoneyParser;
use Zend\Hydrator\HydratorInterface;
use function array_key_exists;
use function get_class;
use function gettype;
use function is_object;
use function sprintf;

class MoneyHydrator implements HydratorInterface
{
    private $formatter;
    private $parser;

    public function __construct(MoneyFormatter $formatter, MoneyParser $parser)
    {
        $this->formatter = $formatter;
        $this->parser = $parser;
    }

    public function extract($object) : array
    {
        $this->assertMoneyInstance($object);
        /** @var Money $object */
        return [
            'currency' => $object->getCurrency()->getCode(),
            'amount' => $this->formatter->format($object),
        ];
    }

    public function hydrate(array $data, $object)
    {
        $this->assertExpectedArrayStructure($data);
        return $this->parser->parse($data['amount'], $data['currency']);
    }

    private function assertMoneyInstance($object) : void
    {
        if (! $object instanceof Money) {
            throw new Exception\InvalidArgumentException(sprintf(
                'Expected an instance of %s but received %s',
                Money::class,
                is_object($object) ? get_class($object) : gettype($object)
            ));
        }
    }

    private function assertExpectedArrayStructure(array $data) : void
    {
        if (! array_key_exists('currency', $data)) {
            throw new Exception\InvalidArgumentException(
                'Expected the array key \'currency\' to be present in the input array'
            );
        }
        if (! array_key_exists('amount', $data)) {
            throw new Exception\InvalidArgumentException(
                'Expected the array key \'amount\' to be present in the input array'
            );
        }
    }
}
