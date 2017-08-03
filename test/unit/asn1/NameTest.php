<?php

use ASN1\Type\Constructed\Sequence;
use X501\ASN1\Name;
use X501\ASN1\RDN;

/**
 * @group asn1
 */
class NameTest extends PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        $name = Name::fromString("name=one,name=two");
        $this->assertInstanceOf(Name::class, $name);
        return $name;
    }
    
    /**
     * @depends testCreate
     *
     * @param Name $name
     */
    public function testEncode(Name $name)
    {
        $der = $name->toASN1()->toDER();
        $this->assertInternalType("string", $der);
        return $der;
    }
    
    /**
     * @depends testEncode
     *
     * @param string $der
     */
    public function testDecode($der)
    {
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
    public function testRecoded(Name $ref, Name $new)
    {
        $this->assertEquals($ref, $new);
    }
    
    /**
     * @depends testCreate
     *
     * @param Name $name
     */
    public function testAll(Name $name)
    {
        $this->assertContainsOnlyInstancesOf(RDN::class, $name->all());
    }
    
    /**
     * @depends testCreate
     *
     * @param Name $name
     */
    public function testFirstValueOf(Name $name)
    {
        $this->assertEquals("two",
            $name->firstValueOf("name")
                ->stringValue());
    }
    
    /**
     * @depends testCreate
     * @expectedException RuntimeException
     *
     * @param Name $name
     */
    public function testFirstValueOfNotFound(Name $name)
    {
        $name->firstValueOf("cn");
    }
    
    /**
     * @expectedException RuntimeException
     */
    public function testFirstValueOfMultipleFail()
    {
        Name::fromString("name=one+name=two")->firstValueOf("name");
    }
    
    /**
     * @depends testCreate
     *
     * @param Name $name
     */
    public function testCount(Name $name)
    {
        $this->assertCount(2, $name);
    }
    
    /**
     * @depends testCreate
     *
     * @param Name $name
     */
    public function testCountOfType(Name $name)
    {
        $this->assertEquals(2, $name->countOfType("name"));
    }
    
    /**
     * @depends testCreate
     *
     * @param Name $name
     */
    public function testCountOfTypeNone(Name $name)
    {
        $this->assertEquals(0, $name->countOfType("cn"));
    }
    
    /**
     * @depends testCreate
     *
     * @param Name $name
     */
    public function testIterable(Name $name)
    {
        $values = array();
        foreach ($name as $rdn) {
            $values[] = $rdn;
        }
        $this->assertContainsOnlyInstancesOf(RDN::class, $values);
    }
    
    /**
     * @depends testCreate
     *
     * @param Name $name
     */
    public function testString(Name $name)
    {
        $this->assertEquals("name=one,name=two", $name->toString());
    }
    
    /**
     * @depends testCreate
     *
     * @param Name $name
     */
    public function testToString(Name $name)
    {
        $this->assertInternalType("string", strval($name));
    }
}
