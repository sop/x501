<?php

use ASN1\Element;
use X501\StringPrep\TranscodeStep;


/**
 * @group string-prep
 */
class TranscodeStepTest extends PHPUnit_Framework_TestCase
{
	public function testUTF8() {
		static $str = "κόσμε";
		$step = new TranscodeStep(Element::TYPE_UTF8_STRING);
		$this->assertEquals($str, $step->apply($str));
	}
	
	public function testPrintableString() {
		static $str = "ASCII";
		$step = new TranscodeStep(Element::TYPE_PRINTABLE_STRING);
		$this->assertEquals($str, $step->apply($str));
	}
	
	public function testBMP() {
		static $str = "κόσμε";
		$step = new TranscodeStep(Element::TYPE_BMP_STRING);
		$this->assertEquals($str, 
			$step->apply(mb_convert_encoding($str, "UCS-2BE", "UTF-8")));
	}
	
	public function testUniversal() {
		static $str = "κόσμε";
		$step = new TranscodeStep(Element::TYPE_UNIVERSAL_STRING);
		$this->assertEquals($str, 
			$step->apply(mb_convert_encoding($str, "UCS-4BE", "UTF-8")));
	}
	
	public function testTeletex() {
		static $str = "TEST";
		$step = new TranscodeStep(Element::TYPE_T61_STRING);
		$this->assertInternalType("string", $step->apply($str));
	}
	
	/**
	 * @expectedException LogicException
	 */
	public function testInvalidType() {
		$step = new TranscodeStep(Element::TYPE_BOOLEAN);
		$step->apply("TEST");
	}
}
