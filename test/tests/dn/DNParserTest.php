<?php

use ASN1\Type\Primitive\Boolean;
use X501\DN\DNParser;


/**
 * @group dn
 */
class DNParserTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @dataProvider provideParser
	 *
	 * @param string $dn Distinguished name
	 * @param array $expected Parser result
	 */
	public function testParser($dn, $expected) {
		$result = DNParser::parseString($dn);
		$this->assertEquals($expected, $result);
	}
	
	public function provideParser() {
		return array(
			/* @formatter:off */
			[
				// single attribute
				"cn=name",
				[[["cn", "name"]]]
			],[
				// uppercase name
				"CN=name",
				[[["CN", "name"]]]
			],[
				// uppercase value
				"C=FI",
				[[["C", "FI"]]]
			],[
				// multiple name-components
				"cn=one,cn=two",
				[[["cn", "two"]], [["cn", "one"]]]
			],[
				// multiple attributes in name-component
				"cn=one+cn=two",
				[[["cn", "one"], ["cn", "two"]]]
			],[
				// multiple name-components and attributes
				"cn=one+cn=two,cn=three+cn=four",
				[ [["cn", "three"], ["cn", "four"]],
				  [["cn", "one"], ["cn", "two"]] ]
			],[
				// empty attribute value
				"cn=",
				[[["cn", ""]]]
			],[
				// ignorable whitespace between name-components
				"cn = one , cn = two",
				[[["cn", "two"]], [["cn", "one"]]]
			],[
				// ignorable whitespace between attributes
				"cn = one + cn = two",
				[[["cn", "one"], ["cn", "two"]]]
			],[
				// escaped whitespace
				"cn=one\ ,cn=\ two",
				[[["cn", " two"]], [["cn", "one "]]]
			],[
				// escaped and ignorable whitespace
				"cn = one\  , cn = \ two",
				[[["cn", " two"]], [["cn", "one "]]]
			],[
				// empty value with whitespace
				"cn = ",
				[[["cn", ""]]]
			],[
				// OID
				"1.2.3.4=val",
				[[["1.2.3.4", "val"]]]
			],[
				// OID with prefix
				"oid.1.2.3.4=val",
				[[["1.2.3.4", "val"]]]
			],[
				// OID with uppercase prefix
				"OID.1.2.3.4=val",
				[[["1.2.3.4", "val"]]]
			],[
				// special characters
				'cn=\,\=\+\<\>\#\;\\\\\"',
				[[["cn", ',=+<>#;\\"']]]
			],[
				// space inside attribute value
				"cn=one two",
				[[["cn", "one two"]]]
			],[
				// consecutive spaces inside attribute value
				"cn=one   two",
				[[["cn", "one   two"]]]
			],[
				// quotation
				'cn="value"',
				[[["cn", "value"]]]
			],[
				// quote many
				'cn="one",cn="two"',
				[[["cn", "two"]], [["cn", "one"]]]
			],[
				// quoted special characters
				'cn=",=+<>#;\\\\\""',
				[[["cn", ',=+<>#;\\"']]]
			],[
				// quoted whitespace
				'cn="   "',
				[[["cn", '   ']]]
			],[
				// hexpair
				'cn=\\20',
				[[["cn", ' ']]]
			],[
				// hexstring
				'cn=#0101ff',
				[[["cn", new Boolean(true)]]]
			],[
				// semicolon separator
				"cn=one;cn=two",
				[[["cn", "two"]], [["cn", "one"]]]
			]
			/* @formatter:on */
		);
	}
}
