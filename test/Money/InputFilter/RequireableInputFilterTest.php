<?php
declare(strict_types=1);

namespace ACETest\Money\InputFilter;

use ACE\Money\InputFilter\RequireableInputFilter;
use ACETest\Money\TestCase;

class RequireableInputFilterTest extends TestCase
{
    public function testSetRequired() : void
    {
        $input = new RequireableInputFilter();
        $this->assertTrue($input->isRequired());
        $input->setRequired(false);
        $this->assertFalse($input->isRequired());
    }

    public function testRequiredInputsAreRequiredByDefault() : void
    {
        $input = new RequireableInputFilter();
        $input->add([
            'name' => 'test',
            'required' => true,
        ]);
        $input->setData([]);
        $this->assertFalse($input->isValid());
        $input->setData(['test' => null]);
        $this->assertFalse($input->isValid());
        $input->setData(['test' => 'Foo']);
        $this->assertTrue($input->isValid());
    }

    public function testEntireInputFilterCanBeMadeOptional() : void
    {
        $input = new RequireableInputFilter();
        $input->add([
            'name' => 'test',
            'required' => true,
        ]);
        $input->setRequired(false);
        $input->setData([]);
        $this->assertTrue($input->isValid());
        $input->setData(['test' => null]);
        $this->assertTrue($input->isValid());
        $input->setData(['test' => 'Foo']);
        $this->assertTrue($input->isValid());
    }

    public function testNestedInputIsRequiredByDefault() : void
    {
        $input = new RequireableInputFilter();
        $input->add([
            'name' => 'test',
            'required' => true,
        ]);
        $child = new RequireableInputFilter();
        $child->add([
            'name' => 'child',
            'required' => true,
        ]);
        $input->add($child, 'nested');
        $input->setData([
            'test' => 'Foo',
        ]);
        $this->assertFalse($input->isValid());
        $messages = $input->getMessages();
        $this->assertArrayHasKey('nested', $messages);
        $this->assertIsArray($messages['nested']);
        $this->assertArrayHasKey('child', $messages['nested']);
    }

    public function testNestedInputCanBeOptional() : void
    {
        $input = new RequireableInputFilter();
        $input->add([
            'name' => 'test',
            'required' => true,
        ]);
        $child = new RequireableInputFilter();
        $child->add([
            'name' => 'child',
            'required' => true,
        ]);
        $child->setRequired(false);
        $input->add($child, 'nested');
        $input->setData([
            'test' => 'Foo',
        ]);
        $this->assertTrue($input->isValid());
        $messages = $input->getMessages();
        $this->assertArrayNotHasKey('nested', $messages);
    }

    public function testNestedFiltersAreValidatedIfNonEmptyEvenIfParentFilterIsOptional() : void
    {
        $input = new RequireableInputFilter();
        $input->setRequired(false);
        $input->add([
            'name' => 'test',
            'required' => true,
        ]);
        $child = new RequireableInputFilter();
        $child->add([
            'name' => 'child',
            'required' => true,
        ]);
        $input->add($child, 'nested');
        $input->setData([
            'test' => null,
            'nested' => [
                'child' => 'Foo',
            ],
        ]);
        $this->assertFalse($input->isValid());
        $messages = $input->getMessages();
        $this->assertArrayNotHasKey('nested', $messages);
        $this->assertArrayHasKey('test', $messages);
    }
}
