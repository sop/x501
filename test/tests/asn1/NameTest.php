<?php

use ASN1\Type\Constructed\Sequence;
use X501\ASN1\Name;
use X501\ASN1\RDN;


/**
 * @group asn1
 */
class NameTest extends PHPUnit_Framework_TestCase
{
	public function testCreate() {
		$name = Name::fromString("name=one,name=two");
		$this->assertInstanceOf(Name::class, $name);
		return $name;
	}
	
	/**
	 * @depends testCreate
	 *
	 * @param Name $name
	 */
	public function testEncode(Name $name) {
		$der = $name->toASN1()->toDER();
		$this->assertTrue(is_string($der));
		return $der;
	}
	
	/**
	 * @depends testEncode
	 *
	 * @param string $der
	 */
	public function testDecode($der) {
		$name = Name::fromASN1(Sequence::fromDER($der));
		$this->assertInstanceOf(Name::class, $name);
		return $name;
	}
	
	/**
	 * @depends testCreate
	 * @depends testDecode
	 *
	 * @param Name $ref
	 * @param Name $new
	 */
	public function testRecoded(Name $ref, Name $new) {
		$this->assertEquals($ref, $new);
	}
	
	/**
	 * @depends testCreate
	 *
	 * @param Name $name
	 */
	public function testCount(Name $name) {
		$this->assertCount(2, $name);
	}
	
	/**
	 * @depends testCreate
	 *
	 * @param Name $name
	 */
	public function testIterable(Name $name) {
		$rdns = array();
		foreach ($name as $rdn) {
			$rdns[] = $rdn;
		}
		$this->assertCount(2, $rdns);
	}
	
	/**
	 * @depends testCreate
	 *
	 * @param Name $name
	 */
	public function testString(Name $name) {
		$this->assertEquals("name=one,name=two", $name->toString());
	}
}
