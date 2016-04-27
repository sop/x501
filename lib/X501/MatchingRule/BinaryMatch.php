<?php

namespace X501\MatchingRule;


/**
 * Implements binary matching rule.
 *
 * Generally used only by UnknownAttribute and custom attributes.
 */
class BinaryMatch extends MatchingRule
{
	public function compare($assertion, $value) {
		return strcmp($assertion, $value) == 0;
	}
}
