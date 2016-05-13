<?php

use ASN1\Element;
use X501\MatchingRule\CaseExactMatch;


/**
 * @group matching-rule
 */
class CaseExactMatchTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @dataProvider provideMatch
	 *
	 * @param string $assertion
	 * @param string $value
	 * @param bool $expected
	 */
	public function testMatch($assertion, $value, $expected) {
		$rule = new CaseExactMatch(Element::TYPE_UTF8_STRING);
		$this->assertEquals($expected, $rule->compare($assertion, $value));
	}
	
	public function provideMatch() {
		return array(
			/* @formatter:off */
			["abc", "abc", true],
			["ABC", "abc", false],
			[" abc ", "abc", true],
			["abc", " abc ", true],
			["a b c", "a  b  c", true],
			["abc", "abcd", false],
			["", "", true],
			["", " ", true]
			/* @formatter:on */
		);
	}
}
