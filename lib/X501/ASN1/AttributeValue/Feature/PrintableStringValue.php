<?php

declare(strict_types = 1);

namespace X501\ASN1\AttributeValue\Feature;

use ASN1\Element;
use ASN1\Type\UnspecifiedType;
use ASN1\Type\Primitive\PrintableString;
use X501\ASN1\AttributeValue\AttributeValue;
use X501\DN\DNParser;
use X501\MatchingRule\CaseIgnoreMatch;

/**
 * Base class for attribute values having <i>PrintableString</i> syntax.
 */
abstract class PrintableStringValue extends AttributeValue
{
    /**
     * String value.
     *
     * @var string $_string
     */
    protected $_string;
    
    /**
     * Constructor
     *
     * @param string $value String value
     */
    public function __construct(string $value)
    {
        $this->_string = $value;
    }
    
    /**
     *
     * @see AttributeValue::fromASN1
     * @param UnspecifiedType $el
     * @return self
     */
    public static function fromASN1(UnspecifiedType $el): self
    {
        return new static($el->asPrintableString()->string());
    }
    
    /**
     *
     * @see AttributeValue::toASN1
     * @return PrintableString
     */
    public function toASN1(): PrintableString
    {
        return new PrintableString($this->_string);
    }
    
    /**
     *
     * @see AttributeValue::stringValue
     * @return string
     */
    public function stringValue(): string
    {
        return $this->_string;
    }
    
    /**
     *
     * @see AttributeValue::equalityMatchingRule
     * @return CaseIgnoreMatch
     */
    public function equalityMatchingRule()
    {
        // default to caseIgnoreMatch
        return new CaseIgnoreMatch(Element::TYPE_PRINTABLE_STRING);
    }
    
    /**
     *
     * @see AttributeValue::rfc2253String
     * @return string
     */
    public function rfc2253String(): string
    {
        return DNParser::escapeString($this->_transcodedString());
    }
    
    /**
     *
     * @see AttributeValue::_transcodedString
     * @return string
     */
    protected function _transcodedString(): string
    {
        // PrintableString maps directly to UTF-8
        return $this->_string;
    }
}
