<?php

namespace X501\StringPrep;


/**
 * Implements 'Insignificant Space Handling' step of the Internationalized
 * String Preparation as specified by RFC 4518.
 *
 * @link https://tools.ietf.org/html/rfc4518#section-2.6.1
 */
class InsignificantSpaceStep implements PrepareStep
{
	/**
	 *
	 * @see \X501\StringPrep\StringPrep::prepare()
	 * @param string $string UTF-8 encoded string
	 * @return string
	 */
	public function apply($string) {
		// if value contains no non-space characters
		if (preg_match('/^\p{Zs}*$/u', $string)) {
			return "  ";
		}
		// trim leading and trailing spaces
		$string = preg_replace('/^\p{Zs}+/u', '', $string);
		$string = preg_replace('/\p{Zs}+$/u', '', $string);
		// convert inner space sequences to two U+0020 characters
		$string = preg_replace('/\p{Zs}+/u', "  ", $string);
		return " $string ";
	}
}
