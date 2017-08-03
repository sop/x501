<?php

use X501\StringPrep\InsignificantNonSubstringSpaceStep;

/**
 * @group string-prep
 */
class InsignificantSpaceStepTest extends PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider provideApplyNonSubstring
     *
     * @param string $string
     * @param string $expected
     */
    public function testApplyNonSubstring($string, $expected)
    {
        $step = new InsignificantNonSubstringSpaceStep();
        $this->assertEquals($expected, $step->apply($string));
    }
    
    public function provideApplyNonSubstring()
    {
        static $nb_space = "\xc2\xa0";
        static $en_space = "\xe2\x80\x82";
        static $em_space = "\xe2\x80\x83";
        return array(
            /* @formatter:off */
            ["", "  "],
            [" ", "  "],
            ["{$nb_space}{$en_space}{$em_space}", "  "],
            ["abc", " abc "],
            ["  abc   ", " abc "],
            ["a bc", " a  bc "],
            ["a{$nb_space}{$en_space}{$em_space}bc", " a  bc "]
            /* @formatter:on */
        );
    }
}
