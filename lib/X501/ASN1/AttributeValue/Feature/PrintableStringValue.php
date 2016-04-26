<?php

namespace X501\ASN1\AttributeValue\Feature;

use ASN1\Element;
use ASN1\Type\Primitive\PrintableString;
use X501\DN\DNParser;


/**
 * Trait for attribute values having <i>PrintableString</i> syntax.
 */
trait PrintableStringValue
{
	/**
	 * String value
	 *
	 * @var string $_string
	 */
	protected $_string;
	
	public static function fromASN1(Element $el) {
		$el->expectType(Element::TYPE_PRINTABLE_STRING);
		return new self($el->str());
	}
	
	public function toASN1() {
		return new PrintableString($this->_string);
	}
	
	public function rfc2253String() {
		return DNParser::escapeString($this->_transcodedString());
	}
	
	protected function _transcodedString() {
		// PrintableString maps directly to UTF-8
		return $this->_string;
	}
}
