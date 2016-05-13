<?php

use ASN1\Type\Constructed\Set;
use X501\ASN1\AttributeTypeAndValue;
use X501\ASN1\AttributeValue\NameValue;
use X501\ASN1\RDN;


/**
 * @group asn1
 */
class RDNTest extends PHPUnit_Framework_TestCase
{
	public function testCreate() {
		$rdn = RDN::fromAttributeValues(new NameValue("one"), 
			new NameValue("two"));
		$this->assertInstanceOf(RDN::class, $rdn);
		return $rdn;
	}
	
	/**
	 * @depends testCreate
	 *
	 * @param RDN $rdn
	 */
	public function testEncode(RDN $rdn) {
		$der = $rdn->toASN1()->toDER();
		$this->assertInternalType("string", $der);
		return $der;
	}
	
	/**
	 * @depends testEncode
	 *
	 * @param string $der
	 */
	public function testDecode($der) {
		$rdn = RDN::fromASN1(Set::fromDER($der));
		$this->assertInstanceOf(RDN::class, $rdn);
		return $rdn;
	}
	
	/**
	 * @depends testCreate
	 * @depends testDecode
	 *
	 * @param RDN $ref
	 * @param RDN $new
	 */
	public function testRecoded(RDN $ref, RDN $new) {
		$this->assertEquals($ref, $new);
	}
	
	/**
	 * @depends testCreate
	 *
	 * @param RDN $rdn
	 */
	public function testAll(RDN $rdn) {
		$this->assertContainsOnlyInstancesOf(AttributeTypeAndValue::class, 
			$rdn->all());
	}
	
	/**
	 * @depends testCreate
	 *
	 * @param RDN $rdn
	 */
	public function testCount(RDN $rdn) {
		$this->assertCount(2, $rdn);
	}
	
	/**
	 * @depends testCreate
	 *
	 * @param RDN $rdn
	 */
	public function testIterable(RDN $rdn) {
		$values = array();
		foreach ($rdn as $tv) {
			$values[] = $tv;
		}
		$this->assertContainsOnlyInstancesOf(AttributeTypeAndValue::class, 
			$values);
	}
	
	/**
	 * @depends testCreate
	 *
	 * @param RDN $rdn
	 */
	public function testString(RDN $rdn) {
		$this->assertEquals("name=one+name=two", $rdn->toString());
	}
	
	/**
	 * @depends testCreate
	 *
	 * @param RDN $rdn
	 */
	public function testToString(RDN $rdn) {
		$this->assertInternalType("string", strval($rdn));
	}
	
	/**
	 * @expectedException UnexpectedValueException
	 */
	public function testCreateFail() {
		new RDN();
	}
}
