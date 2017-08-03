<?php

use X501\StringPrep\NormalizeStep;

/**
 * @group string-prep
 */
class NormalizeStepTest extends PHPUnit_Framework_TestCase
{
    public function testApply()
    {
        $source = "ฉันกินกระจกได้ แต่มันไม่ทำให้ฉันเจ็บ";
        $step = new NormalizeStep();
        $expected = normalizer_normalize($source, \Normalizer::FORM_KC);
        $this->assertEquals($expected, $step->apply($source));
    }
}
