<?php

declare(strict_types = 1);

use PHPUnit\Framework\TestCase;
use Sop\ASN1\Element;
use Sop\ASN1\Type\Primitive\NullType;
use Sop\ASN1\Type\UnspecifiedType;
use Sop\X501\ASN1\AttributeValue\CommonNameValue;
use Sop\X501\ASN1\AttributeValue\Feature\DirectoryString;

/**
 * @group asn1
 * @group value
 *
 * @internal
 */
class DirectoryStringTest extends TestCase
{
    public function testFromASN1InvalidType()
    {
        $this->expectException(\UnexpectedValueException::class);
        DirectoryString::fromASN1(new UnspecifiedType(new NullType()));
    }

    public function testToASN1InvalidType()
    {
        $value = new CommonNameValue('name', Element::TYPE_NULL);
        $this->expectException(\UnexpectedValueException::class);
        $value->toASN1();
    }

    public function testTeletexValue()
    {
        $value = new CommonNameValue('name', Element::TYPE_T61_STRING);
        $this->assertEquals('#1404' . bin2hex('name'), $value->rfc2253String());
    }
}
