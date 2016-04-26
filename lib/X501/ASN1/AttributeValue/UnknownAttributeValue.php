<?php

namespace X501\ASN1\AttributeValue;

use ASN1\Element;


/**
 * Class hold ASN.1 structure of an unimplemented attribute value.
 */
class UnknownAttributeValue extends AttributeValue
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
	
	/**
	 *
	 * @see \X501\ASN1\AttributeValue\AttributeValue::toASN1()
	 * @return Element
	 */
	public function toASN1() {
		return $this->_element;
	}
	
	/**
	 *
	 * @see \X501\ASN1\AttributeValue\AttributeValue::rfc2253String()
	 * @return string
	 */
	public function rfc2253String() {
		// return DER encoding as a hexstring
		return "#" . bin2hex($this->_element->toDER());
	}
}
