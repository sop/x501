<?php

use X501\ASN1\Name;


/**
 * @group name
 */
class NameCompareTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @dataProvider provideCompareNames
	 *
	 * @param string $dn1
	 * @param string $dn2
	 * @param bool $expected
	 */
	public function testCompareNames($dn1, $dn2, $expected) {
		$n1 = Name::fromString($dn1);
		$n2 = Name::fromString($dn2);
		$this->assertEquals($expected, $n1->equals($n2));
	}
	
	/**
	 * @dataProvider provideCompareNames
	 *
	 * @param string $dn1
	 * @param string $dn2
	 * @param bool $expected
	 */
	public function testToString($dn1, $dn2, $expected) {
		$n1 = Name::fromString($dn1);
		$this->assertEquals($dn1, $n1->toString());
		$n2 = Name::fromString($dn2);
		$this->assertEquals($dn2, $n2->toString());
	}
	
	public function provideCompareNames() {
		return array(
			/* @formatter:off */
			["cn=test", "cn=test", true],
			["cn=test1", "cn=test2", false],
			["cn=test,givenName=derp", "cn=test,givenName=derp", true],
			["cn=test,givenName=derp", "cn=test,givenName=herp", false],
			["cn=test+cn=alias", "cn=test+cn=alias", true],
			["cn=test+cn=alias", "cn=test+cn=aliaz", false],
			["cn=test,givenName=derp", "cn=test", false],
			["cn=test+cn=derp", "cn=test", false],
			["1.3.6.1.3=#0101ff", "1.3.6.1.3=#0101ff", true],
			["1.3.6.1.3=#0101ff", "1.3.6.1.3=#010100", false],
			["c=FI", "c=FI", true],
			/* @formatter:on */
		);
	}
}
