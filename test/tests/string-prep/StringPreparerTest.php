<?php

use ASN1\Element;
use X501\StringPrep\StringPreparer;


/**
 * @group string-prep
 */
class StringPreparerTest extends PHPUnit_Framework_TestCase
{
	public function testCreate() {
		$preparer = StringPreparer::forStringType(Element::TYPE_UTF8_STRING);
		$this->assertInstanceOf(StringPreparer::class, $preparer);
		return $preparer;
	}
	
	/**
	 * @depends testCreate
	 *
	 * @param StringPreparer $preparer
	 */
	public function testWithCaseFolding(StringPreparer $preparer) {
		$preparer = $preparer->withCaseFolding(true);
		$this->assertInstanceOf(StringPreparer::class, $preparer);
		return $preparer;
	}
	
	/**
	 * @depends testWithCaseFolding
	 * 
	 * @param StringPreparer $preparer
	 */
	public function testPrepare(StringPreparer $preparer) {
		$str = $preparer->prepare("TEST");
		$this->assertEquals(" test ", $str);
	}
}
