<?php

namespace X501\MatchingRule;

use X501\StringPrep\StringPreparer;

/**
 * Implements 'caseIgnoreMatch' matching rule.
 *
 * @link https://tools.ietf.org/html/rfc4517#section-4.2.11
 */
class CaseIgnoreMatch extends StringPrepMatchingRule
{
    /**
     * Constructor.
     *
     * @param int $string_type ASN.1 string type tag
     */
    public function __construct($string_type)
    {
        parent::__construct(
            StringPreparer::forStringType($string_type)->withCaseFolding(true));
    }
}
