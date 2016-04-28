<?php

namespace X501\MatchingRule;


/**
 * Base class for attribute matching rules.
 *
 * @link https://tools.ietf.org/html/rfc4517#section-4
 */
abstract class MatchingRule
{
	/**
	 * Compare attribute value to assertion.
	 *
	 * @param mixed $assertion Value to assert
	 * @param mixed $value Attribute value
	 * @return bool|null True if value matches. Null shall be returned if match
	 *         evaluates to Undefined.
	 */
	abstract public function compare($assertion, $value);
}