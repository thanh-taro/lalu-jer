<?php

namespace LaLu\JER;

class MetaTest extends \PHPUnit_Framework_TestCase
{
    public function testBasic()
    {
        $object = new Meta();
        $this->assertClassHasAttribute('version', Meta::class);
        $this->assertClassHasAttribute('attributes', Meta::class);
        $this->assertAttributeEquals('1.0', 'version', $object);
        $this->assertAttributeEquals(null, 'attributes', $object);
        $this->assertEquals([], $object->getJsonStruct());
        $this->assertEquals('1.0', $object->getVersion());
        $this->assertEquals([], $object->getAttributes());
        $this->assertNull($object->getData());
        $this->assertTrue($object->setVersion('1.1.0'));
        $this->assertAttributeEquals('1.1.0', 'version', $object);
        $this->assertFalse($object->getJsonStruct());
        $this->assertEquals('1.1.0', $object->getVersion());
        $this->assertEquals([], $object->getAttributes());
        $this->assertNull($object->getData());

        $object = new Meta(['version' => '1.0']);
        $this->assertAttributeEquals('1.0', 'version', $object);
        $this->assertAttributeEquals(null, 'attributes', $object);
        $this->assertEquals([], $object->getJsonStruct());
        $this->assertEquals('1.0', $object->getVersion());
        $this->assertEquals([], $object->getAttributes());
        $this->assertNull($object->getData());
    }
}
