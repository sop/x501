<?php

use ASN1\Type\Primitive\Boolean;
use X501\DN\DNParser;

/**
 * @group dn
 */
class DNParserTest extends PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider provideParseString
     *
     * @param string $dn Distinguished name
     * @param array $expected Parser result
     */
    public function testParseString($dn, $expected)
    {
        $result = DNParser::parseString($dn);
        $this->assertEquals($expected, $result);
    }
    
    public function provideParseString()
    {
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
    
    /**
     * @dataProvider provideEscapeString
     *
     * @param string $str
     * @param string $expected
     */
    public function testEscapeString($str, $expected)
    {
        $escaped = DNParser::escapeString($str);
        $this->assertEquals($expected, $escaped);
    }
    
    public function provideEscapeString()
    {
        return array(
            /* @formatter:off */
            [',', '\,'],
            ['+', '\+'],
            ['"', '\"'],
            ['\\', '\\\\'],
            ['<', '\<'],
            ['>', '\>'],
            [';', '\;'],
            ['test ', 'test\ '],
            ['test  ', 'test \ '],
            [' test', '\ test'],
            ['  test', '\  test'],
            ["\x00", '\00'],
            // UTF-8 'ZERO WIDTH SPACE'
            ["\xE2\x80\x8B", '\E2\80\8B']
            /* @formatter:on */
        );
    }
    
    /**
     * @expectedException UnexpectedValueException
     */
    public function testUnexpectedNameEnd()
    {
        DNParser::parseString("cn=#05000");
    }
    
    /**
     * @expectedException UnexpectedValueException
     */
    public function testInvalidTypeAndValuePair()
    {
        DNParser::parseString("cn");
    }
    
    /**
     * @expectedException UnexpectedValueException
     */
    public function testInvalidAttributeType()
    {
        DNParser::parseString("#00=fail");
    }
    
    /**
     * @expectedException UnexpectedValueException
     */
    public function testUnexpectedQuotation()
    {
        DNParser::parseString("cn=fa\"il");
    }
    
    /**
     * @expectedException UnexpectedValueException
     */
    public function testInvalidHexString()
    {
        DNParser::parseString("cn=#.");
    }
    
    /**
     * @expectedException UnexpectedValueException
     */
    public function testInvalidHexDER()
    {
        DNParser::parseString("cn=#badcafee");
    }
    
    /**
     * @expectedException UnexpectedValueException
     */
    public function testUnexpectedPairEnd()
    {
        DNParser::parseString("cn=\\");
    }
    
    /**
     * @expectedException UnexpectedValueException
     */
    public function testUnexpectedHexPairEnd()
    {
        DNParser::parseString("cn=\\f");
    }
    
    /**
     * @expectedException UnexpectedValueException
     */
    public function testInvalidHexPair()
    {
        DNParser::parseString("cn=\\xx");
    }
}
