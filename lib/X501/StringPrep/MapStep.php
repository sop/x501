<?php

namespace X501\StringPrep;


/**
 * Implements 'Map' step of the Internationalized String Preparation
 * as specified by RFC 4518.
 *
 * @link https://tools.ietf.org/html/rfc4518#section-2.2
 */
class MapStep implements PrepareStep
{
	/**
	 * Whether to apply case folding.
	 *
	 * @var bool $_fold
	 */
	protected $_fold;
	
	/**
	 * Constructor
	 *
	 * @param bool $fold_case Whether to apply case folding
	 */
	public function __construct($fold_case = false) {
		$this->_fold = $fold_case;
	}
	
	/**
	 *
	 * @see \X501\StringPrep\StringPrep::prepare()
	 * @param string $string UTF-8 encoded string
	 * @return string
	 */
	public function apply($string) {
		// @todo Implement character mappings
		if ($this->_fold) {
			$string = mb_convert_case($string, MB_CASE_LOWER, "UTF-8");
		}
		return $string;
	}
}