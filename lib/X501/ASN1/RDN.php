<?php

namespace X501\ASN1;

use ASN1\Type\Constructed\Set;
use ASN1\Type\UnspecifiedType;
use X501\ASN1\AttributeValue\AttributeValue;


/**
 * Implements <i>RelativeDistinguishedName</i> ASN.1 type.
 *
 * @link
 *       https://www.itu.int/ITU-T/formal-language/itu-t/x/x501/2012/InformationFramework.html#InformationFramework.RelativeDistinguishedName
 */
class RDN implements \Countable, \IteratorAggregate
{
	/**
	 * Attributes.
	 *
	 * @var AttributeTypeAndValue[] $_attribs
	 */
	protected $_attribs;
	
	/**
	 * Constructor
	 *
	 * @param AttributeTypeAndValue ...$attribs One or more attributes
	 */
	public function __construct(AttributeTypeAndValue ...$attribs) {
		if (!count($attribs)) {
			throw new \UnexpectedValueException(
				"RDN must have at least one AttributeTypeAndValue.");
		}
		$this->_attribs = $attribs;
	}
	
	/**
	 * Convenience method to initialize RDN from AttributeValue objects.
	 *
	 * @param AttributeValue ...$values One or more attributes
	 * @return self
	 */
	public static function fromAttributeValues(AttributeValue ...$values) {
		$attribs = array_map(
			function (AttributeValue $value) {
				return new AttributeTypeAndValue(
					new AttributeType($value->oid()), $value);
			}, $values);
		return new self(...$attribs);
	}
	
	/**
	 * Initialize from ASN.1.
	 *
	 * @param Set $set
	 * @return self
	 */
	public static function fromASN1(Set $set) {
		$attribs = array_map(
			function (UnspecifiedType $el) {
				return AttributeTypeAndValue::fromASN1($el->asSequence());
			}, $set->elements());
		return new self(...$attribs);
	}
	
	/**
	 * Generate ASN.1 structure.
	 *
	 * @return Set
	 */
	public function toASN1() {
		$elements = array_map(
			function (AttributeTypeAndValue $tv) {
				return $tv->toASN1();
			}, $this->_attribs);
		$set = new Set(...$elements);
		return $set->sortedSetOf();
	}
	
	/**
	 * Get name-component string conforming to RFC 2253.
	 *
	 * @link https://tools.ietf.org/html/rfc2253#section-2.2
	 * @return string
	 */
	public function toString() {
		$parts = array_map(
			function (AttributeTypeAndValue $tv) {
				return $tv->toString();
			}, $this->_attribs);
		return implode("+", $parts);
	}
	
	/**
	 * Check whether RDN is semantically equal to other.
	 *
	 * @param self $other
	 * @return bool
	 */
	public function equals(self $other) {
		// if attribute count doesn't match
		if (count($this) != count($other)) {
			return false;
		}
		$attribs1 = $this->_attribs;
		$attribs2 = $other->_attribs;
		// if there's multiple attributes, sort using SET OF rules
		if (count($attribs1) > 1) {
			$attribs1 = self::fromASN1($this->toASN1())->_attribs;
			$attribs2 = self::fromASN1($other->toASN1())->_attribs;
		}
		for ($i = count($attribs1) - 1; $i >= 0; --$i) {
			$tv1 = $attribs1[$i];
			$tv2 = $attribs2[$i];
			if (!$tv1->equals($tv2)) {
				return false;
			}
		}
		return true;
	}
	
	/**
	 * Get all AttributeTypeAndValue objects.
	 *
	 * @return AttributeTypeAndValue[]
	 */
	public function all() {
		return $this->_attribs;
	}
	
	/**
	 *
	 * @see Countable::count()
	 * @return int
	 */
	public function count() {
		return count($this->_attribs);
	}
	
	/**
	 *
	 * @see IteratorAggregate::getIterator()
	 * @return \ArrayIterator
	 */
	public function getIterator() {
		return new \ArrayIterator($this->_attribs);
	}
	
	/**
	 *
	 * @return string
	 */
	public function __toString() {
		return $this->toString();
	}
}
