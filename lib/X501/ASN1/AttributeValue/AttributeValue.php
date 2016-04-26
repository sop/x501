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
	/**
	 * Mapping from attribute type OID to attribute value class name.
	 *
	 * @var array
	 */
	private static $_oidToCls = array(
		/* @formatter:off */
		AttributeType::OID_COMMON_NAME => CommonNameValue::class,
		AttributeType::OID_SURNAME => SurnameValue::class,
		AttributeType::OID_SERIAL_NUMBER => SerialNumberValue::class,
		AttributeType::OID_COUNTRY_NAME => CountryNameValue::class,
		AttributeType::OID_LOCALITY_NAME => LocalityNameValue::class,
		AttributeType::OID_STATE_OR_PROVINCE_NAME => StateOrProvinceNameValue::class,
		AttributeType::OID_ORGANIZATION_NAME => OrganizationNameValue::class,
		AttributeType::OID_ORGANIZATIONAL_UNIT_NAME => OrganizationalUnitNameValue::class,
		AttributeType::OID_TITLE => TitleValue::class,
		AttributeType::OID_DESCRIPTION => DescriptionValue::class,
		AttributeType::OID_NAME => NameValue::class,
		AttributeType::OID_GIVEN_NAME => GivenNameValue::class,
		AttributeType::OID_PSEUDONYM => PseudonymValue::class,
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
	 * Get attribute value as an UTF-8 string conforming to RFC 4518.
	 *
	 * @link https://tools.ietf.org/html/rfc4518#section-2.1
	 * @return string
	 */
	abstract protected function _transcodedString();
	
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
			return new UnknownAttributeValue($oid, $el);
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
		// step 1: Transcode
		$value = $this->_transcodedString();
		// step 2: Map (NOT IMPLEMENTED)
		// step 3: Normalize
		$value = normalizer_normalize($value, \Normalizer::NFKD);
		// step 4: Prohibit (NOT IMPLEMENTED)
		// step 5: Check bidi (NOT IMPLEMENTED)
		// step 6: Insignificant Character Handling
		// @todo: consider attribute type
		$value = self::_applyInsignificantSpaceHandling($value);
		return $value;
	}
	
	/**
	 * Apply insignificant space handling conforming to RFC 4518.
	 *
	 * @link https://tools.ietf.org/html/rfc4518#section-2.6
	 * @param string $str
	 * @return string
	 */
	private static function _applyInsignificantSpaceHandling($str) {
		// if value contains no non-space characters
		if (preg_match('/^\p{Zs}*$/u', $str)) {
			return "  ";
		}
		// trim leading and trailing spaces
		$str = preg_replace('/^\p{Zs}+/u', '', $str);
		$str = preg_replace('/\p{Zs}+$/u', '', $str);
		// convert inner space sequences to two U+0020 characters
		$str = preg_replace('/\p{Zs}+/u', "  ", $str);
		return " $str ";
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
