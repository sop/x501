<?php

use ASN1\Type\Constructed\Sequence;
use X501\ASN1\Attribute;
use X501\ASN1\AttributeType;
use X501\ASN1\AttributeValue\AttributeValue;
use X501\ASN1\AttributeValue\CommonNameValue;
use X501\ASN1\AttributeValue\NameValue;


/**
 * @group asn1
 */
class AttributeTest extends PHPUnit_Framework_TestCase
{
	public function testCreate() {
		$attr = Attribute::fromAttributeValues(new NameValue("one"), 
			new NameValue("two"));
		$this->assertInstanceOf(Attribute::class, $attr);
		return $attr;
	}
	
	/**
	 * @depends testCreate
	 *
	 * @param Attribute $attr
	 */
	public function testEncode(Attribute $attr) {
		$der = $attr->toASN1()->toDER();
		$this->assertInternalType("string", $der);
		return $der;
	}
	
	/**
	 * @depends testEncode
	 *
	 * @param string $der
	 */
	public function testDecode($der) {
		$attr = Attribute::fromASN1(Sequence::fromDER($der));
		$this->assertInstanceOf(Attribute::class, $attr);
		return $attr;
	}
	
	/**
	 * @depends testCreate
	 * @depends testDecode
	 *
	 * @param Attribute $ref
	 * @param Attribute $new
	 */
	public function testRecoded(Attribute $ref, Attribute $new) {
		$this->assertEquals($ref, $new);
	}
	
	/**
	 * @depends testCreate
	 *
	 * @param Attribute $attr
	 */
	public function testType(Attribute $attr) {
		$this->assertEquals(AttributeType::fromName("name"), $attr->type());
	}
	
	/**
	 * @depends testCreate
	 *
	 * @param Attribute $attr
	 */
	public function testFirst(Attribute $attr) {
		$this->assertEquals("one", $attr->first()
			->rfc2253String());
	}
	
	/**
	 * @depends testCreate
	 *
	 * @param Attribute $attr
	 */
	public function testValues(Attribute $attr) {
		$this->assertContainsOnlyInstancesOf(AttributeValue::class, 
			$attr->values());
	}
	
	/**
	 * @depends testCreate
	 *
	 * @param Attribute $attr
	 */
	public function testCount(Attribute $attr) {
		$this->assertCount(2, $attr);
	}
	
	/**
	 * @depends testCreate
	 *
	 * @param Attribute $attr
	 */
	public function testIterable(Attribute $attr) {
		$values = array();
		foreach ($attr as $value) {
			$values[] = $value;
		}
		$this->assertContainsOnlyInstancesOf(AttributeValue::class, $values);
	}
	
	/**
	 * @expectedException LogicException
	 */
	public function testCreateMismatch() {
		Attribute::fromAttributeValues(new NameValue("name"), 
			new CommonNameValue("cn"));
	}
	
	/**
	 * @expectedException LogicException
	 */
	public function testEmptyFromValuesFail() {
		Attribute::fromAttributeValues();
	}
	
	public function testCreateEmpty() {
		$attr = new Attribute(AttributeType::fromName("cn"));
		$this->assertInstanceOf(Attribute::class, $attr);
		return $attr;
	}
	
	/**
	 * @depends testCreateEmpty
	 * @expectedException LogicException
	 *
	 * @param Attribute $attr
	 */
	public function testEmptyFirstFail(Attribute $attr) {
		$attr->first();
	}
}
