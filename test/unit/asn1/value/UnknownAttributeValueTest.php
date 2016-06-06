<?php

use ASN1\Type\Primitive\UTF8String;
use ASN1\Type\UnspecifiedType;
use X501\ASN1\AttributeValue\AttributeValue;
use X501\ASN1\AttributeValue\UnknownAttributeValue;


/**
 * @group asn1
 * @group value
 */
class UnknownAttributeValueTest extends PHPUnit_Framework_TestCase
{
	const OID = "1.3.6.1.3";
	
	public function testCreate() {
		$val = AttributeValue::fromASN1ByOID(self::OID, 
			new UnspecifiedType(new UTF8String("Test")));
		$this->assertInstanceOf(UnknownAttributeValue::class, $val);
		return $val;
	}
	
	/**
	 * @depends testCreate
	 *
	 * @param AttributeValue $val
	 */
	public function testOID(AttributeValue $val) {
		$this->assertEquals(self::OID, $val->oid());
	}
	
	/**
	 * @depends testCreate
	 *
	 * @param AttributeValue $val
	 */
	public function testANS1(AttributeValue $val) {
		$this->assertInstanceOf(UTF8String::class, $val->toASN1());
	}
	
	/**
	 * @depends testCreate
	 *
	 * @param AttributeValue $val
	 */
	public function testString(AttributeValue $val) {
		$this->assertEquals("#0c04" . bin2hex("Test"), $val->rfc2253String());
	}
	
	/**
	 * @depends testCreate
	 *
	 * @param AttributeValue $val
	 */
	public function testToString(AttributeValue $val) {
		$this->assertInternalType("string", strval($val));
	}
}
