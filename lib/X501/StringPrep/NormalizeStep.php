<?php

namespace X501\StringPrep;


/**
 * Implements 'Normalize' step of the Internationalized String Preparation
 * as specified by RFC 4518.
 *
 * @link https://tools.ietf.org/html/rfc4518#section-2.3
 */
class NormalizeStep implements PrepareStep
{
	/**
	 *
	 * @see \X501\StringPrep\StringPrep::prepare()
	 * @param string $string UTF-8 encoded string
	 * @return string
	 */
	public function apply($string) {
		return normalizer_normalize($string, \Normalizer::NFKC);
	}
}
