<?php

declare(strict_types = 1);

namespace X501\MatchingRule;

/**
 * Implements binary matching rule.
 *
 * Generally used only by UnknownAttribute and custom attributes.
 */
class BinaryMatch extends MatchingRule
{
    /**
     *
     * {@inheritdoc}
     */
    public function compare($assertion, $value)
    {
        return strcmp($assertion, $value) == 0;
    }
}
