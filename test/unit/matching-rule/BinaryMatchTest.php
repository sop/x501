<?php

use X501\MatchingRule\BinaryMatch;

/**
 * @group matching-rule
 */
class BinaryMatchTest extends PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider provideMatch
     *
     * @param string $assertion
     * @param string $value
     * @param bool $expected
     */
    public function testMatch($assertion, $value, $expected)
    {
        $rule = new BinaryMatch();
        $this->assertEquals($expected, $rule->compare($assertion, $value));
    }
    
    public function provideMatch()
    {
        return array(
            /* @formatter:off */
            ["abc", "abc", true],
            ["ABC", "abc", false],
            [" abc ", "abc", false],
            ["abc", " abc ", false],
            ["a b c", "a  b  c", false],
            ["abc", "abcd", false],
            ["", "", true],
            ["", " ", false]
            /* @formatter:on */
        );
    }
}
