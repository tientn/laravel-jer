<?php

namespace LaravelSoft\JER;

class TraitImplement
{
    use ExceptionHandlerTrait;
}

class ExceptionHandlerTraitTest extends \PHPUnit_Framework_TestCase
{
    private $traitMockObject;
    private $traitObject;

    public function setUp()
    {
        $this->traitMockObject = $this->getMockForTrait(ExceptionHandlerTrait::class);
        $this->traitObject = new TraitImplement();
    }

    public function testBasic()
    {
        $this->assertClassHasStaticAttribute('HTTP_STATUS_CODES', TraitImplement::class);
        $this->assertClassHasAttribute('jsonapiVersion', TraitImplement::class);
        $this->assertEquals($this->traitObject->jsonapiVersion, 'v1.0.0');
        $this->assertEquals($this->traitMockObject->jsonapiVersion, 'v1.0.0');
        $this->assertAttributeEquals('v1.0.0', 'jsonapiVersion', $this->traitObject);
        $this->assertAttributeEquals('v1.0.0', 'jsonapiVersion', $this->traitMockObject);
    }
}
