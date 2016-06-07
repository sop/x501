<?php

namespace X501\ASN1;

use ASN1\Element;
use ASN1\Type\Constructed\Sequence;
use X501\ASN1\AttributeValue\AttributeValue;
use X501\ASN1\Feature\TypedAttribute;


/**
 * Implements <i>AttributeTypeAndValue</i> ASN.1 type.
 *
 * @link
 *       https://www.itu.int/ITU-T/formal-language/itu-t/x/x501/2012/InformationFramework.html#InformationFramework.AttributeTypeAndValue
 */
class AttributeTypeAndValue
{
	use TypedAttribute;
	
	/**
	 * Attribute value.
	 *
	 * @var AttributeValue $_value
	 */
	protected $_value;
	
	/**
	 * Constructor
	 *
	 * @param AttributeType $type Attribute type
	 * @param AttributeValue $value Attribute value
	 */
	public function __construct(AttributeType $type, AttributeValue $value) {
		$this->_type = $type;
		$this->_value = $value;
	}
	
	/**
	 * Initialize from ASN.1.
	 *
	 * @param Sequence $seq
	 * @return self
	 */
	public static function fromASN1(Sequence $seq) {
		$type = AttributeType::fromASN1($seq->at(0)->asObjectIdentifier());
		$value = AttributeValue::fromASN1ByOID($type->oid(), $seq->at(1));
		return new self($type, $value);
	}
	
	/**
	 * Convenience method to initialize from attribute value.
	 *
	 * @param AttributeValue $value Attribute value
	 * @return self
	 */
	public static function fromAttributeValue(AttributeValue $value) {
		return new self(new AttributeType($value->oid()), $value);
	}
	
	/**
	 * Get attribute value.
	 *
	 * @return AttributeValue
	 */
	public function value() {
		return $this->_value;
	}
	
	/**
	 * Generate ASN.1 structure.
	 *
	 * @return Element
	 */
	public function toASN1() {
		return new Sequence($this->_type->toASN1(), $this->_value->toASN1());
	}
	
	/**
	 * Get attributeTypeAndValue string conforming to RFC 2253.
	 *
	 * @link https://tools.ietf.org/html/rfc2253#section-2.3
	 * @return string
	 */
	public function toString() {
		return $this->_type->typeName() . "=" . $this->_value->rfc2253String();
	}
	
	/**
	 * Check whether attribute is semantically equal to other.
	 *
	 * @param AttributeTypeAndValue $other Object to compare to
	 * @return bool
	 */
	public function equals(AttributeTypeAndValue $other) {
		// check that attribute types match
		if ($this->oid() !== $other->oid()) {
			return false;
		}
		$matcher = $this->_value->equalityMatchingRule();
		$result = $matcher->compare($this->_value->stringValue(), 
			$other->_value->stringValue());
		// match
		if ($result) {
			return true;
		}
		// no match or Undefined
		return false;
	}
	
	/**
	 *
	 * @return string
	 */
	public function __toString() {
		return $this->toString();
	}
}
