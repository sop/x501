<?php

namespace X501\StringPrep;

/**
 * Implements 'Check bidi' step of the Internationalized String Preparation
 * as specified by RFC 4518.
 *
 * @link https://tools.ietf.org/html/rfc4518#section-2.5
 */
class CheckBidiStep implements PrepareStep
{
    /**
     *
     * @param string $string UTF-8 encoded string
     * @return string
     */
    public function apply($string)
    {
        // @todo Implement
        return $string;
    }
}
