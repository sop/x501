<?php

namespace X501\ASN1;

use ASN1\Element;
use ASN1\Type\Constructed\Sequence;
use X501\ASN1\AttributeValue\AttributeValue;
use X501\DN\DNParser;


/**
 * Implements <i>Name</i> ASN.1 type.
 *
 * Since <i>Name</i> is a CHOICE only supporting <i>RDNSequence</i> type,
 * this class implements <i>RDNSequence</i> semantics as well.
 *
 * @link
 *       https://www.itu.int/ITU-T/formal-language/itu-t/x/x501/2012/InformationFramework.html#InformationFramework.Name
 */
class Name implements \Countable, \IteratorAggregate
{
	/**
	 * Relative distinguished name components.
	 *
	 * @var RDN[] $_rdns
	 */
	protected $_rdns;
	
	/**
	 * Constructor
	 *
	 * @param RDN ...$rdns RDN components
	 */
	public function __construct(RDN ...$rdns) {
		$this->_rdns = $rdns;
	}
	
	/**
	 * Initialize from ASN.1.
	 *
	 * @param Sequence $seq
	 * @return self
	 */
	public static function fromASN1(Sequence $seq) {
		$rdns = array_map(
			function (Element $el) {
				return RDN::fromASN1($el->expectType(Element::TYPE_SET));
			}, $seq->elements());
		return new self(...$rdns);
	}
	
	/**
	 * Initialize from distinguished name string.
	 *
	 * @link https://tools.ietf.org/html/rfc1779
	 * @param string $str
	 * @return self
	 */
	public static function fromString($str) {
		$rdns = array();
		foreach (DNParser::parseString($str) as $nameComponent) {
			$attribs = array();
			foreach ($nameComponent as list($name, $val)) {
				$type = AttributeType::fromName($name);
				// hexstrings are parsed to ASN.1 elements
				if ($val instanceof Element) {
					$el = $val;
				} else {
					$el = AttributeType::asn1StringForType($type->oid(), $val);
				}
				$value = AttributeValue::fromASN1ByOID($type->oid(), $el);
				$attribs[] = new AttributeTypeAndValue($type, $value);
			}
			$rdns[] = new RDN(...$attribs);
		}
		return new self(...$rdns);
	}
	
	/**
	 * Generate ASN.1 structure.
	 *
	 * @return Sequence
	 */
	public function toASN1() {
		$elements = array_map(
			function (RDN $rdn) {
				return $rdn->toASN1();
			}, $this->_rdns);
		return new Sequence(...$elements);
	}
	
	/**
	 * Get distinguised name string conforming to RFC 2253.
	 *
	 * @link https://tools.ietf.org/html/rfc2253#section-2.1
	 * @return string
	 */
	public function toString() {
		$parts = array_map(
			function (RDN $rdn) {
				return $rdn->toString();
			}, array_reverse($this->_rdns));
		return implode(",", $parts);
	}
	
	/**
	 * Whether name is semantically equal to other.
	 * Comparison conforms to rfc4518 string preparation algorithm.
	 *
	 * @param self $other
	 * @return bool
	 */
	public function equals(self $other) {
		// if RDN count doesn't match
		if (count($this) != count($other)) {
			return false;
		}
		for ($i = count($this) - 1; $i >= 0; --$i) {
			$rdn1 = $this->_rdns[$i];
			$rdn2 = $other->_rdns[$i];
			if (!$rdn1->equals($rdn2)) {
				return false;
			}
		}
		return true;
	}
	
	/**
	 *
	 * @see Countable::count()
	 * @return int
	 */
	public function count() {
		return count($this->_rdns);
	}
	
	/**
	 *
	 * @see IteratorAggregate::getIterator()
	 * @return \ArrayIterator
	 */
	public function getIterator() {
		return new \ArrayIterator($this->_rdns);
	}
	
	public function __toString() {
		return $this->toString();
	}
}
