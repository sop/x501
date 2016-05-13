<?php

use ASN1\Type\StringType;
use X501\ASN1\AttributeType;
use X501\ASN1\AttributeValue\AttributeValue;
use X501\ASN1\AttributeValue\CommonNameValue;
use X501\ASN1\AttributeValue\CountryNameValue;
use X501\ASN1\AttributeValue\DescriptionValue;
use X501\ASN1\AttributeValue\GivenNameValue;
use X501\ASN1\AttributeValue\LocalityNameValue;
use X501\ASN1\AttributeValue\NameValue;
use X501\ASN1\AttributeValue\OrganizationalUnitNameValue;
use X501\ASN1\AttributeValue\OrganizationNameValue;
use X501\ASN1\AttributeValue\PseudonymValue;
use X501\ASN1\AttributeValue\SerialNumberValue;
use X501\ASN1\AttributeValue\StateOrProvinceNameValue;
use X501\ASN1\AttributeValue\SurnameValue;
use X501\ASN1\AttributeValue\TitleValue;


/**
 * @group asn1
 * @group value
 */
class ValueInitializationTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @dataProvider provideStringAttribClasses
	 */
	public function testCreate($cls, $oid) {
		$el = AttributeType::asn1StringForType($oid, "Test");
		$val = AttributeValue::fromASN1ByOID($oid, $el);
		$this->assertInstanceOf($cls, $val);
	}
	
	/**
	 * @dataProvider provideStringAttribClasses
	 */
	public function testASN1($cls, $oid) {
		$val = new $cls("Test");
		$el = $val->toASN1();
		$this->assertInstanceOf(StringType::class, $el);
	}
	
	public function provideStringAttribClasses() {
		return array(
			/* @formatter:off */
			[CommonNameValue::class, AttributeType::OID_COMMON_NAME],
			[SurnameValue::class, AttributeType::OID_SURNAME],
			[SerialNumberValue::class, AttributeType::OID_SERIAL_NUMBER],
			[CountryNameValue::class, AttributeType::OID_COUNTRY_NAME],
			[LocalityNameValue::class, AttributeType::OID_LOCALITY_NAME],
			[StateOrProvinceNameValue::class, AttributeType::OID_STATE_OR_PROVINCE_NAME],
			[OrganizationNameValue::class, AttributeType::OID_ORGANIZATION_NAME],
			[OrganizationalUnitNameValue::class, AttributeType::OID_ORGANIZATIONAL_UNIT_NAME],
			[TitleValue::class, AttributeType::OID_TITLE],
			[DescriptionValue::class, AttributeType::OID_DESCRIPTION],
			[NameValue::class, AttributeType::OID_NAME],
			[GivenNameValue::class, AttributeType::OID_GIVEN_NAME],
			[PseudonymValue::class, AttributeType::OID_PSEUDONYM],
			/* @formatter:on */
		);
	}
}
