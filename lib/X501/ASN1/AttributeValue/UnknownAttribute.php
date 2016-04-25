<?php

namespace X501\ASN1\AttributeValue;

use ASN1\Element;


class UnknownAttribute extends AttributeValue
{
	/**
	 * ASN.1 element.
	 *
	 * @var Element $_element
	 */
	protected $_element;
	
	/**
	 * Constructor
	 *
	 * @param string $oid
	 * @param Element $el
	 */
	public function __construct($oid, Element $el) {
		$this->_oid = $oid;
		$this->_element = $el;
	}
	
	public function toASN1() {
		return $this->_element;
	}
	
	public function rfc2253String() {
		return "#" . bin2hex($this->_element->toDER());
	}
}
