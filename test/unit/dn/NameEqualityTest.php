<?php

use X501\ASN1\Name;


/**
 * @group dn
 */
class NameEqualityTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @dataProvider provideEqual
	 *
	 * @param string $dn1
	 * @param string $dn2
	 */
	public function testEqual($dn1, $dn2) {
		$result = Name::fromString($dn1)->equals(Name::fromString($dn2));
		$this->assertTrue($result);
	}
	
	public function provideEqual() {
		return array(
			/* @formatter:off */
			// binary equal
			["cn=one", "cn=one"],
			// case-insensitive
			["cn=one", "cn=ONE"],
			// insignificant whitespace
			["cn=one", "cn=\ one\ "],
			// repeated inner whitespace
			["cn=o n e ", "cn=\ o  n  e\ "],
			// no-break space
			["cn=on e", "cn=on\xC2\xA0e"],
			// multiple attributes
			["cn=one,cn=two", "cn=one,cn=two"],
			["cn=one,cn=two", "cn=ONE,cn=TWO"],
			["cn=o n e,cn=two", "cn=\ o  n  e\  , cn=\ two\  "],
			/* @formatter:on */
		);
	}
	
	/**
	 * @dataProvider provideUnequal
	 *
	 * @param string $dn1
	 * @param string $dn2
	 */
	public function testUnequal($dn1, $dn2) {
		$result = Name::fromString($dn1)->equals(Name::fromString($dn2));
		$this->assertFalse($result);
	}
	
	public function provideUnequal() {
		return array(
			/* @formatter:off */
			// value mismatch
			["cn=one", "cn=two"],
			["cn=one,cn=two", "cn=one,cn=three"],
			// attribute mismatch
			["cn=one", "name=one"],
			["cn=one,cn=two", "cn=one,name=two"],
			/* @formatter:on */
		);
	}
}
