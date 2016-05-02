<?php

use X501\StringPrep\ProhibitStep;


/**
 * @group string-prep
 */
class ProhibitStepTest extends PHPUnit_Framework_TestCase
{
	public function testApply() {
		$str = "Test";
		$step = new ProhibitStep();
		$this->assertEquals($str, $step->apply($str));
	}
}
