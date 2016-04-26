<?php

namespace X501\ASN1\AttributeValue\Syntax;

use ASN1\Element;
use ASN1\Type\Primitive\BMPString;
use ASN1\Type\Primitive\PrintableString;
use ASN1\Type\Primitive\T61String;
use ASN1\Type\Primitive\UniversalString;
use ASN1\Type\Primitive\UTF8String;
use X501\ASN1\AttributeValue\AttributeValue;


/**
 * Base class for attribute values having <i>(Unbounded)DirectoryString</i>
 * as a syntax.
 *
 * @link
 *       https://www.itu.int/ITU-T/formal-language/itu-t/x/x520/2012/SelectedAttributeTypes.html#SelectedAttributeTypes.UnboundedDirectoryString
 */
abstract class DirectoryString extends AttributeValue
{
	/**
	 * Teletex string syntax.
	 *
	 * @var int
	 */
	const TELETEX = Element::TYPE_T61_STRING;
	
	/**
	 * Printable string syntax.
	 *
	 * @var int
	 */
	const PRINTABLE = Element::TYPE_PRINTABLE_STRING;
	
	/**
	 * BMP string syntax.
	 *
	 * @var int
	 */
	const BMP = Element::TYPE_BMP_STRING;
	
	/**
	 * Universal string syntax.
	 *
	 * @var int
	 */
	const UNIVERSAL = Element::TYPE_UNIVERSAL_STRING;
	
	/**
	 * UTF-8 string syntax.
	 *
	 * @var int
	 */
	const UTF8 = Element::TYPE_UTF8_STRING;
	
	/**
	 * Mapping from syntax enumeration to ASN.1 class name.
	 *
	 * @var array
	 */
	private static $_tagToCls = array(
		/* @formatter:off */
		self::TELETEX => T61String::class,
		self::PRINTABLE => PrintableString::class,
		self::UNIVERSAL => UniversalString::class,
		self::UTF8 => UTF8String::class,
		self::BMP => BMPString::class
		/* @formatter:on */
	);
	
	/**
	 * ASN.1 type tag for the chosen syntax.
	 *
	 * @var int $_stringTag
	 */
	protected $_stringTag;
	
	/**
	 * String value
	 *
	 * @var string $_value
	 */
	protected $_value;
	
	/**
	 * Constructor
	 *
	 * @param string $value String value
	 * @param int $string_tag Syntax choice
	 */
	public function __construct($value, $string_tag) {
		$this->_value = $value;
		$this->_stringTag = $string_tag;
	}
	
	/**
	 * Initialize from ASN.1.
	 *
	 * @param Element $el
	 * @throws \UnexpectedValueException
	 * @return self
	 */
	public static function fromASN1(Element $el) {
		$tag = $el->tag();
		if (!isset(self::$_tagToCls[$tag])) {
			throw new \UnexpectedValueException(
				"Type " . Element::tagToName($tag) .
					 " is not valid DirectoryString");
		}
		return new static($el->str(), $tag);
	}
	
	/**
	 *
	 * @see \X501\ASN1\AttributeValue\AttributeValue::toASN1()
	 * @return Element
	 */
	public function toASN1() {
		if (!isset(self::$_tagToCls[$this->_stringTag])) {
			throw new \UnexpectedValueException(
				"Type " . Element::tagToName($this->_stringTag) .
					 " is not valid DirectoryString");
		}
		$cls = self::$_tagToCls[$this->_stringTag];
		return new $cls($this->_value);
	}
	
	/**
	 *
	 * @see \X501\ASN1\AttributeValue\AttributeValue::rfc2253String()
	 * @return string
	 */
	public function rfc2253String() {
		$value = $this->_transcoded();
		// TeletexString is encoded as binary
		if ($this->_stringTag !== self::TELETEX) {
			$value = self::_escapeRfc2253Value($value);
		}
		return $value;
	}
	
	/**
	 *
	 * @see \X501\ASN1\AttributeValue\AttributeValue::rfc4518String()
	 * @return string
	 */
	public function rfc4518String() {
		// step 1: Transcode
		$value = $this->_transcoded();
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
	 * Transcode value to UTF-8.
	 *
	 * @link https://tools.ietf.org/html/rfc4518#section-2.1
	 * @return string
	 */
	private function _transcoded() {
		switch ($this->_stringTag) {
		// UTF-8 string as is
		case self::UTF8:
			return $this->_value;
		// PrintableString maps directly to UTF-8
		case self::PRINTABLE:
			return $this->_value;
		case self::BMP:
			return mb_convert_encoding($this->_value, "UTF-8", "UCS-2BE");
		case self::UNIVERSAL:
			return mb_convert_encoding($this->_value, "UTF-8", "UCS-4BE");
		// TeletexString is such a hairy ball that we just 
		// encode it as a "non string"
		case self::TELETEX:
			return "#" . bin2hex($this->toASN1()->toDER());
		default:
			throw new \LogicException("Unsupported string type");
		}
	}
	
	/**
	 * Escape value conforming to RFC 2253.
	 *
	 * @link https://tools.ietf.org/html/rfc2253#section-2.4
	 * @param string $str
	 * @return string
	 */
	private static function _escapeRfc2253Value($str) {
		// one of the characters ",", "+", """, "\", "<", ">" or ";"
		$str = preg_replace('/([,\+"\\\<\>;])/u', '\\\\$1', $str);
		// a space character occurring at the end of the string
		$str = preg_replace('/( )$/u', '\\\\$1', $str);
		// a space or "#" character occurring at the beginning of the string
		$str = preg_replace('/^([ #])/u', '\\\\$1', $str);
		// implementation specific special characters
		$str = preg_replace_callback('/([\pC])/u', 
			function ($m) {
				$octets = str_split(bin2hex($m[1]), 2);
				return implode("", 
					array_map(
						function ($octet) {
							return '\\' . strtoupper($octet);
						}, $octets));
			}, $str);
		return $str;
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
}
