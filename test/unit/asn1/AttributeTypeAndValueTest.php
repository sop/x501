<?php

use ASN1\Type\Constructed\Sequence;
use X501\ASN1\AttributeTypeAndValue;
use X501\ASN1\AttributeValue\NameValue;

/**
 * @group asn1
 */
class AttributeTypeAndValueTest extends PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        $atv = AttributeTypeAndValue::fromAttributeValue(new NameValue("one"));
        $this->assertInstanceOf(AttributeTypeAndValue::class, $atv);
        return $atv;
    }
    
    /**
     * @depends testCreate
     *
     * @param AttributeTypeAndValue $atv
     */
    public function testEncode(AttributeTypeAndValue $atv)
    {
        $der = $atv->toASN1()->toDER();
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
        $atv = AttributeTypeAndValue::fromASN1(Sequence::fromDER($der));
        $this->assertInstanceOf(AttributeTypeAndValue::class, $atv);
        return $atv;
    }
    
    /**
     * @depends testCreate
     * @depends testDecode
     *
     * @param AttributeTypeAndValue $ref
     * @param AttributeTypeAndValue $new
     */
    public function testRecoded(AttributeTypeAndValue $ref,
        AttributeTypeAndValue $new)
    {
        $this->assertEquals($ref, $new);
    }
    
    /**
     * @depends testCreate
     *
     * @param AttributeTypeAndValue $atv
     */
    public function testValue(AttributeTypeAndValue $atv)
    {
        $this->assertEquals("one", $atv->value()
            ->rfc2253String());
    }
    
    /**
     * @depends testCreate
     *
     * @param AttributeTypeAndValue $atv
     */
    public function testString(AttributeTypeAndValue $atv)
    {
        $this->assertEquals("name=one", $atv->toString());
    }
    
    /**
     * @depends testCreate
     *
     * @param AttributeTypeAndValue $atv
     */
    public function testToString(AttributeTypeAndValue $atv)
    {
        $this->assertInternalType("string", strval($atv));
    }
}
