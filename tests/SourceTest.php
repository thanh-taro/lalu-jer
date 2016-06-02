<?php

namespace LaLu\JER;

class SourceTest extends \PHPUnit_Framework_TestCase
{
    public function testBasic()
    {
        $object = new Source();
        $this->assertClassHasAttribute('version', Source::class);
        $this->assertClassHasAttribute('attributes', Source::class);
        $this->assertAttributeEquals('1.0', 'version', $object);
        $this->assertAttributeEquals(null, 'attributes', $object);
        $this->assertEquals(['pointer', 'parameter'], $object->getJsonStruct());
        $this->assertEquals('1.0', $object->getVersion());
        $this->assertEquals([], $object->getAttributes());
        $this->assertNull($object->getData());
        $this->assertTrue($object->setVersion('1.1.0'));
        $this->assertAttributeEquals('1.1.0', 'version', $object);
        $this->assertFalse($object->getJsonStruct());
        $this->assertEquals('1.1.0', $object->getVersion());
        $this->assertEquals([], $object->getAttributes());
        $this->assertNull($object->getData());

        $object = new Source(['version' => '1.0']);
        $this->assertAttributeEquals('1.0', 'version', $object);
        $this->assertAttributeEquals(null, 'attributes', $object);
        $this->assertEquals(['pointer', 'parameter'], $object->getJsonStruct());
        $this->assertEquals('1.0', $object->getVersion());
        $this->assertEquals([], $object->getAttributes());
        $this->assertNull($object->getData());
        $object->pointer = 'This is pointer';
        $this->assertEquals('This is pointer', $object->pointer);
        $this->assertEquals(['pointer' => 'This is pointer'], $object->getAttributes());
        $this->assertEquals(['pointer' => 'This is pointer'], $object->getData());
        $object->foo_baz = 'Foo Baz';
        $this->assertEquals('This is pointer', $object->pointer);
        $this->assertEquals(['pointer' => 'This is pointer'], $object->getAttributes());
        $this->assertEquals(['pointer' => 'This is pointer'], $object->getData());
        $attributes = [
            'pointer' => 'This is pointer',
            'parameter' => 'This is parameter',
            'foo_baz' => 'Foo Baz',
        ];
        $jsonStruct = $object->getJsonStruct();
        $realAttributes = [];
        foreach ($attributes as $key => $value) {
            $object->$key = $value;
            if (in_array($key, $jsonStruct)) {
                $realAttributes[$key] = $value;
                $this->assertEquals($value, $object->$key);
            } else {
                $this->assertNull($object->$key);
            }
        }
        $this->assertEquals($realAttributes, $object->getAttributes());
        $this->assertEquals($realAttributes, $object->getData());
    }
}
