<?php

namespace X501\ASN1\AttributeValue;

use ASN1\Element;
use X501\ASN1\Attribute;
use X501\ASN1\AttributeType;
use X501\ASN1\AttributeTypeAndValue;


/**
 * Base class for attribute values.
 *
 * @link
 *       https://www.itu.int/ITU-T/formal-language/itu-t/x/x501/2012/InformationFramework.html#InformationFramework.AttributeValue
 */
abstract class AttributeValue
{
	private static $_oidToCls = array(
		/* @formatter:off */
		AttributeType::OID_NAME => NameValue::class,
		/* @formatter:on */
	);
	
	/**
	 * OID of the attribute type.
	 *
	 * @var string $_oid
	 */
	protected $_oid;
	
	/**
	 * Generate ASN.1 element.
	 *
	 * @return Element
	 */
	abstract public function toASN1();
	
	/**
	 * Get attribute value as a string conforming to RFC 2253.
	 *
	 * @link https://tools.ietf.org/html/rfc2253#section-2.4
	 * @return string
	 */
	abstract public function rfc2253String();
	
	/**
	 * Initialize from ASN.1.
	 *
	 * @param Element $el
	 * @return self
	 */
	public static function fromASN1(Element $el) {
		throw new \BadMethodCallException(
			"ASN.1 parsing must be implemented in concrete class");
	}
	
	/**
	 * Initialize from ASN.1 with given OID hint.
	 *
	 * @param string $oid Attribute's OID
	 * @param Element $el
	 * @return self
	 */
	public static function fromASN1ByOID($oid, Element $el) {
		if (!isset(self::$_oidToCls[$oid])) {
			return new UnknownAttribute($oid, $el);
		}
		$cls = self::$_oidToCls[$oid];
		return $cls::fromASN1($el);
	}
	
	/**
	 * Get attribute type's OID.
	 *
	 * @return string
	 */
	public function oid() {
		return $this->_oid;
	}
	
	/**
	 * Get attribute value as a string by first applying internationalized
	 * string preparation as specified in RFC 4518.
	 *
	 * @link https://tools.ietf.org/html/rfc4518
	 * @return string
	 */
	public function rfc4518String() {
		// override in derived classes when applicable
		return $this->rfc2253String();
	}
	
	/**
	 * Get Attribute object with this as a single value.
	 *
	 * @return Attribute
	 */
	public function toAttribute() {
		return Attribute::fromAttributeValues($this);
	}
	
	/**
	 * Get AttributeTypeAndValue object with this as a value.
	 *
	 * @return AttributeTypeAndValue
	 */
	public function toAttributeTypeAndValue() {
		return AttributeTypeAndValue::fromAttributeValue($this);
	}
	
	public function __toString() {
		return $this->rfc2253String();
	}
}
