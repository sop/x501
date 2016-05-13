<?php

use ASN1\Type\Primitive\NullType;
use X501\ASN1\Attribute;
use X501\ASN1\AttributeTypeAndValue;
use X501\ASN1\AttributeValue\AttributeValue;
use X501\ASN1\AttributeValue\CommonNameValue;


/**
 * @group asn1
 * @group value
 */
class AttributeValueTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @expectedException BadMethodCallException
	 */
	public function testFromASN1BadCall() {
		AttributeValue::fromASN1(new NullType());
	}
	
	public function testToAttribute() {
		$val = new CommonNameValue("name");
		$this->assertInstanceOf(Attribute::class, $val->toAttribute());
	}
	
	public function testToAttributeTypeAndValue() {
		$val = new CommonNameValue("name");
		$this->assertInstanceOf(AttributeTypeAndValue::class, 
			$val->toAttributeTypeAndValue());
	}
}
