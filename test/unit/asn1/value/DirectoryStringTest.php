<?php

use ASN1\Element;
use ASN1\Type\UnspecifiedType;
use ASN1\Type\Primitive\NullType;
use X501\ASN1\AttributeValue\CommonNameValue;
use X501\ASN1\AttributeValue\Feature\DirectoryString;

/**
 * @group asn1
 * @group value
 */
class DirectoryStringTest extends PHPUnit_Framework_TestCase
{
    /**
     * @expectedException UnexpectedValueException
     */
    public function testFromASN1InvalidType()
    {
        DirectoryString::fromASN1(new UnspecifiedType(new NullType()));
    }
    
    /**
     * @expectedException UnexpectedValueException
     */
    public function testToASN1InvalidType()
    {
        $value = new CommonNameValue("name", Element::TYPE_NULL);
        $value->toASN1();
    }
    
    public function testTeletexValue()
    {
        $value = new CommonNameValue("name", Element::TYPE_T61_STRING);
        $this->assertEquals("#1404" . bin2hex("name"), $value->rfc2253String());
    }
}
