<?php

use ASN1\Type\Constructed\Sequence;
use X501\ASN1\Attribute;
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
		$this->assertTrue(is_string($der));
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
		$this->assertCount(2, $attr->values());
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
		$this->assertCount(2, $values);
	}
}
