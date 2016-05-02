<?php

namespace X501\MatchingRule;

use X501\StringPrep\StringPreparer;


/**
 * Base class for matching rules employing string preparement semantics.
 */
abstract class StringPrepMatchingRule extends MatchingRule
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
	 * @param StringPreparer $preparer
	 */
	public function __construct(StringPreparer $preparer) {
		$this->_prep = $preparer;
	}
	
	public function compare($assertion, $value) {
		$assertion = $this->_prep->prepare($assertion);
		$value = $this->_prep->prepare($value);
		return strcmp($assertion, $value) == 0;
	}
}
