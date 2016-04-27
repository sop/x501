<?php

namespace X501\MatchingRule;

use X501\StringPrep\StringPreparer;


/**
 * Implements 'caseIgnoreMatch' matching rule.
 *
 * @link https://tools.ietf.org/html/rfc4517#section-4.2.11
 */
class CaseIgnoreMatch extends MatchingRule
{
	/**
	 * String preparer.
	 *
	 * @var StringPreparer $_prep
	 */
	protected $_prep;
	
	/**
	 * Constructor
	 *
	 * @param int $string_type ASN.1 string type tag
	 */
	public function __construct($string_type) {
		$this->_prep = StringPreparer::forStringType($string_type)->withCaseFolding(
			true);
	}
	
	public function compare($assertion, $value) {
		$assertion = $this->_prep->prepare($assertion);
		$value = $this->_prep->prepare($value);
		return strcmp($assertion, $value) == 0;
	}
}
