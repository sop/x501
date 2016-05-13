<?php

namespace X501\StringPrep;

use ASN1\Element;
use ASN1\Type\Primitive\T61String;


/**
 * Implements 'Transcode' step of the Internationalized String Preparation
 * as specified by RFC 4518.
 *
 * @link https://tools.ietf.org/html/rfc4518#section-2.1
 */
class TranscodeStep implements PrepareStep
{
	/**
	 * ASN.1 type of the string.
	 *
	 * @var int $_type
	 */
	protected $_type;
	
	/**
	 * Constructor
	 *
	 * @param int $type ASN.1 type tag of the string
	 */
	public function __construct($type) {
		$this->_type = $type;
	}
	
	/**
	 *
	 * @see \X501\StringPrep\StringPrep::prepare()
	 * @throws \LogicException If string type is not supported
	 * @param string $string String to prepare
	 * @return string UTF-8 encoded string
	 */
	public function apply($string) {
		switch ($this->_type) {
		// UTF-8 string as is
		case Element::TYPE_UTF8_STRING:
			return $string;
		// PrintableString maps directly to UTF-8
		case Element::TYPE_PRINTABLE_STRING:
			return $string;
		// UCS-2 to UTF-8
		case Element::TYPE_BMP_STRING:
			return mb_convert_encoding($string, "UTF-8", "UCS-2BE");
		// UCS-4 to UTF-8
		case Element::TYPE_UNIVERSAL_STRING:
			return mb_convert_encoding($string, "UTF-8", "UCS-4BE");
		// TeletexString mapping is a local matter.
		// We take a shortcut here and encode it as a hexstring.
		case Element::TYPE_T61_STRING:
			$el = new T61String($string);
			return "#" . bin2hex($el->toDER());
		}
		throw new \LogicException(
			"Unsupported string type " . Element::tagToName($this->_type) . ".");
	}
}
