<?php

namespace X501\MatchingRule;


/**
 * Implements binary matching rule.
 *
 * Used by unknown attribute.
 */
class BinaryMatch extends MatchingRule
{
	public function compare($assertion, $value) {
		return strcmp($assertion, $value) == 0;
	}
}
