<?php

use X501\StringPrep\MapStep;

/**
 * @group string-prep
 */
class MapStepTest extends PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider provideApplyCaseFold
     *
     * @param string $string
     * @param string $expected
     */
    public function testApplyCaseFold($string, $expected)
    {
        $step = new MapStep(true);
        $this->assertEquals($expected, $step->apply($string));
    }
    
    public function provideApplyCaseFold()
    {
        return array(
            /* @formatter:off */
            ["abc", "abc"],
            ["ABC", "abc"],
            /* @formatter:on */
        );
    }
}
