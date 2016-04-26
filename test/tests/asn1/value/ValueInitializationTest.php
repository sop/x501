<?php

use X501\ASN1\AttributeType;
use X501\ASN1\AttributeValue\AttributeValue;
use X501\ASN1\AttributeValue\CommonNameValue;
use X501\ASN1\AttributeValue\CountryNameValue;
use X501\ASN1\AttributeValue\NameValue;


/**
 * @group asn1
 * @group value
 */
class ValueInitializationTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @dataProvider provider
	 */
	public function testCreate($cls, $oid) {
		$el = AttributeType::asn1StringForType($oid, "Test");
		$val = AttributeValue::fromASN1ByOID($oid, $el);
		$this->assertInstanceOf($cls, $val);
	}
	
	public function provider() {
		return array(
			/* @formatter:off */
			[NameValue::class, AttributeType::OID_NAME],
			[CountryNameValue::class, AttributeType::OID_COUNTRY_NAME],
			[CommonNameValue::class, AttributeType::OID_COMMON_NAME]
			/* @formatter:on */
		);
	}
}
