<?php

namespace LaLu\JER;

class ExceptionHandlerTraitTest extends \PHPUnit_Framework_TestCase
{
    private $traitMockObject;

    public function setUp()
    {
        $this->traitMockObject = $this->getMockForTrait(ExceptionHandlerTrait::class);
    }

    public function testBasic()
    {
        $this->assertClassHasStaticAttribute('HTTP_STATUS_CODES', get_class($this->traitMockObject));
        $this->assertClassHasAttribute('jsonapiVersion', get_class($this->traitMockObject));
        $this->assertClassHasAttribute('meta', get_class($this->traitMockObject));
        $this->assertClassHasAttribute('headers', get_class($this->traitMockObject));
        $this->assertEquals($this->traitMockObject->jsonapiVersion, '1.0');
        $this->assertAttributeEquals('1.0', 'jsonapiVersion', $this->traitMockObject);
        $this->assertAttributeEquals(null, 'meta', $this->traitMockObject);
        $this->assertAttributeEquals([], 'headers', $this->traitMockObject);
    }
}
