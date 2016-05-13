<?php

use X501\StringPrep\CheckBidiStep;


/**
 * @group string-prep
 */
class CheckBidiStepTest extends PHPUnit_Framework_TestCase
{
	public function testApply() {
		$str = "Test";
		$step = new CheckBidiStep();
		$this->assertEquals($str, $step->apply($str));
	}
}
