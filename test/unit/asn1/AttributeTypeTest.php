<?php

use ASN1\Type\Primitive\ObjectIdentifier;
use X501\ASN1\AttributeType;

/**
 * @group asn1
 */
class AttributeTypeTest extends PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        $type = AttributeType::fromName("name");
        $this->assertInstanceOf(AttributeType::class, $type);
        return $type;
    }
    
    /**
     * @depends testCreate
     *
     * @param AttributeType $type
     */
    public function testEncode(AttributeType $type)
    {
        $der = $type->toASN1()->toDER();
        $this->assertInternalType("string", $der);
        return $der;
    }
    
    /**
     * @depends testEncode
     *
     * @param string $der
     */
    public function testDecode($der)
    {
        $type = AttributeType::fromASN1(ObjectIdentifier::fromDER($der));
        $this->assertInstanceOf(AttributeType::class, $type);
        return $type;
    }
    
    /**
     * @depends testCreate
     * @depends testDecode
     *
     * @param AttributeType $ref
     * @param AttributeType $new
     */
    public function testRecoded(AttributeType $ref, AttributeType $new)
    {
        $this->assertEquals($ref, $new);
    }
    
    /**
     * @depends testCreate
     *
     * @param AttributeType $type
     */
    public function testOID(AttributeType $type)
    {
        $this->assertEquals(AttributeType::OID_NAME, $type->oid());
    }
    
    /**
     * @depends testCreate
     *
     * @param AttributeType $type
     */
    public function testName(AttributeType $type)
    {
        $this->assertEquals("name", $type->typeName());
    }
    
    public function testUnknownName()
    {
        static $oid = "1.3.6.1.3";
        $type = new AttributeType($oid);
        $this->assertEquals($oid, $type->typeName());
    }
    
    /**
     * @expectedException OutOfBoundsException
     */
    public function testNameToOIDFail()
    {
        AttributeType::attrNameToOID("unknown");
    }
}
