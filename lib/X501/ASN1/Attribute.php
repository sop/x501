<?php

namespace X501\ASN1;

use ASN1\Type\Constructed\Sequence;
use ASN1\Type\Constructed\Set;
use ASN1\Type\UnspecifiedType;
use X501\ASN1\AttributeValue\AttributeValue;
use X501\ASN1\Feature\TypedAttribute;


/**
 * Implements <i>Attribute</i> ASN.1 type.
 *
 * @link
 *       https://www.itu.int/ITU-T/formal-language/itu-t/x/x501/2012/InformationFramework.html#InformationFramework.Attribute
 */
class Attribute implements 
	\Countable, \IteratorAggregate
{
	use TypedAttribute;
	
	/**
	 * Attribute values.
	 *
	 * @var AttributeValue[]
	 */
	protected $_values;
	
	/**
	 * Constructor
	 *
	 * @param AttributeType $type Attribute type
	 * @param AttributeValue ...$values Attribute values
	 */
	public function __construct(AttributeType $type, AttributeValue ...$values) {
		// check that attribute values have correct oid
		array_walk($values, 
			function (AttributeValue $value) use ($type) {
				if ($value->oid() != $type->oid()) {
					throw new \LogicException("Attribute OID mismatch.");
				}
			});
		$this->_type = $type;
		$this->_values = $values;
	}
	
	/**
	 * Initialize from ASN.1.
	 *
	 * @param Sequence $seq
	 * @return self
	 */
	public static function fromASN1(Sequence $seq) {
		$type = AttributeType::fromASN1($seq->at(0)->asObjectIdentifier());
		$values = array_map(
			function (UnspecifiedType $el) use ($type) {
				return AttributeValue::fromASN1ByOID($type->oid(), $el);
			}, $seq->at(1)
				->asSet()
				->elements());
		return new self($type, ...$values);
	}
	
	/**
	 * Convenience method to initialize from attribute values.
	 *
	 * @param AttributeValue ...$values One or more values
	 * @throws \LogicException
	 * @return self
	 */
	public static function fromAttributeValues(AttributeValue ...$values) {
		// we need at least one value to determine OID
		if (!count($values)) {
			throw new \LogicException("No values.");
		}
		$oid = reset($values)->oid();
		return new self(new AttributeType($oid), ...$values);
	}
	
	/**
	 * Get first value of the attribute.
	 *
	 * @throws \LogicException
	 * @return AttributeValue
	 */
	public function first() {
		if (!count($this->_values)) {
			throw new \LogicException("Attribute contains no values.");
		}
		return $this->_values[0];
	}
	
	/**
	 * Get all values.
	 *
	 * @return AttributeValue[]
	 */
	public function values() {
		return $this->_values;
	}
	
	/**
	 * Generate ASN.1 structure.
	 *
	 * @return Sequence
	 */
	public function toASN1() {
		$values = array_map(
			function (AttributeValue $value) {
				return $value->toASN1();
			}, $this->_values);
		$valueset = new Set(...$values);
		return new Sequence($this->_type->toASN1(), $valueset->sortedSetOf());
	}
	
	/**
	 * Cast attribute values to another AttributeValue class.
	 *
	 * This method is generally used to cast UnknownAttributeValue values
	 * to specific objects when class is declared outside this package.
	 *
	 * The new class must be derived from AttributeValue and have the same OID
	 * as current attribute values.
	 *
	 * @param string $cls AttributeValue class name
	 * @throws \LogicException
	 * @return self
	 */
	public function castValues($cls) {
		$refl = new \ReflectionClass($cls);
		if (!$refl->isSubclassOf(AttributeValue::class)) {
			throw new \LogicException(
				"$cls must be derived from " . AttributeValue::class . ".");
		}
		$oid = $this->oid();
		$values = array_map(
			function (AttributeValue $value) use ($cls, $oid) {
				$value = $cls::fromSelf($value);
				if ($value->oid() != $oid) {
					throw new \LogicException("Attribute OID mismatch.");
				}
				return $value;
			}, $this->_values);
		return self::fromAttributeValues(...$values);
	}
	
	/**
	 *
	 * @see Countable::count()
	 * @return int
	 */
	public function count() {
		return count($this->_values);
	}
	
	/**
	 *
	 * @see IteratorAggregate::getIterator()
	 * @return \ArrayIterator
	 */
	public function getIterator() {
		return new \ArrayIterator($this->_values);
	}
}
