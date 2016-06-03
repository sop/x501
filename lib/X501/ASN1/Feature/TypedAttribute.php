<?php

namespace X501\ASN1\Feature;

use X501\ASN1\AttributeType;


/**
 * Trait for attributes having a type.
 */
trait TypedAttribute
{
	/**
	 * Attribute type.
	 *
	 * @var AttributeType $_type
	 */
	protected $_type;
	
	/**
	 * Get attribute type.
	 *
	 * @return AttributeType
	 */
	public function type() {
		return $this->_type;
	}
	
	/**
	 * Get OID of the attribute.
	 *
	 * @return string
	 */
	public function oid() {
		return $this->_type->oid();
	}
}
