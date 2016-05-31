<?php

namespace LaLu\JER;

class ErrorTest extends \PHPUnit_Framework_TestCase
{
    public function testBasic()
    {
        $object = new Error();
        $this->assertClassHasAttribute('version', Error::class);
        $this->assertClassHasAttribute('attributes', Error::class);
        $this->assertAttributeEquals('1.0.0', 'version', $object);
        $this->assertAttributeEquals(null, 'attributes', $object);
        $this->assertEquals(['id', 'links', 'status', 'code', 'title', 'detail', 'source', 'meta'], $object->getJsonStruct());
        $this->assertEquals('1.0.0', $object->getVersion());
        $this->assertEquals([], $object->getAttributes());
        $this->assertNull($object->getData());
        $this->assertTrue($object->setVersion('1.1.0'));
        $this->assertAttributeEquals('1.1.0', 'version', $object);
        $this->assertFalse($object->getJsonStruct());
        $this->assertEquals('1.1.0', $object->getVersion());
        $this->assertEquals([], $object->getAttributes());
        $this->assertNull($object->getData());

        $object = new Error(['version' => '1.0.0']);
        $this->assertAttributeEquals('1.0.0', 'version', $object);
        $this->assertAttributeEquals(null, 'attributes', $object);
        $this->assertEquals(['id', 'links', 'status', 'code', 'title', 'detail', 'source', 'meta'], $object->getJsonStruct());
        $this->assertEquals('1.0.0', $object->getVersion());
        $this->assertEquals([], $object->getAttributes());
        $this->assertNull($object->getData());
        $object->title = 'This is error title';
        $this->assertEquals('This is error title', $object->title);
        $this->assertEquals(['title' => 'This is error title'], $object->getAttributes());
        $this->assertEquals(['title' => 'This is error title'], $object->getData());
        $object->foo_baz = 'Foo Baz';
        $this->assertEquals('This is error title', $object->title);
        $this->assertEquals(['title' => 'This is error title'], $object->getAttributes());
        $this->assertEquals(['title' => 'This is error title'], $object->getData());
        $attributes = [
            'title' => 'This is error title',
            'detail' => 'This is error detail',
            'status' => 'This is status',
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
        $this->assertEquals($realAttributes, $object->getAttributes(['title', 'detail', 'status']));
        $source = new Source();
        $object->source = $source;
        $this->assertEquals($source, $object->source);
        $source->pointer = 'email';
        $this->assertEquals($source, $object->source);
        $realAttributes['source'] = $source;
        $this->assertEquals($realAttributes, $object->getAttributes());
        $this->assertNotEquals($realAttributes, $object->getData());
        $realAttributes['source'] = $source->getData();
        $this->assertNotEquals($realAttributes, $object->getAttributes());
        $this->assertEquals($realAttributes, $object->getData());

        $this->assertTrue($object->loadOption([]));
        $this->assertTrue($object->setAttributes([]));
        $this->assertTrue($object->setAttributes(['title' => 'This is title']));
    }
}
