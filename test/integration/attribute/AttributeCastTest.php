<?php

use ASN1\Type\Primitive\UTF8String;
use X501\ASN1\Attribute;
use X501\ASN1\AttributeType;
use X501\ASN1\AttributeValue\CommonNameValue;
use X501\ASN1\AttributeValue\DescriptionValue;
use X501\ASN1\AttributeValue\UnknownAttributeValue;

/**
 * @group attribute
 */
class AttributeCastTest extends PHPUnit_Framework_TestCase
{
    private static $_attr;
    
    public static function setUpBeforeClass()
    {
        self::$_attr = new Attribute(
            new AttributeType(AttributeType::OID_COMMON_NAME),
            new UnknownAttributeValue(AttributeType::OID_COMMON_NAME,
                new UTF8String("name")));
    }
    
    public static function tearDownAfterClass()
    {
        self::$_attr = null;
    }
    
    public function testCast()
    {
        $attr = self::$_attr->castValues(CommonNameValue::class);
        $this->assertInstanceOf(CommonNameValue::class, $attr->first());
    }
    
    /**
     * @expectedException LogicException
     */
    public function testInvalidClass()
    {
        self::$_attr->castValues(stdClass::class);
    }
    
    /**
     * @expectedException LogicException
     */
    public function testOIDMismatch()
    {
        self::$_attr->castValues(DescriptionValue::class);
    }
}
