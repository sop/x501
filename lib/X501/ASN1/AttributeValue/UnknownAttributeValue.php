<?php

namespace X501\ASN1\AttributeValue;

use ASN1\Element;
use X501\MatchingRule\BinaryMatch;

/**
 * Class to hold ASN.1 structure of an unimplemented attribute value.
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
     * Constructor.
     *
     * @param string $oid
     * @param Element $el
     */
    public function __construct($oid, Element $el)
    {
        $this->_oid = $oid;
        $this->_element = $el;
    }
    
    /**
     *
     * @see \X501\ASN1\AttributeValue\AttributeValue::toASN1()
     * @return Element
     */
    public function toASN1()
    {
        return $this->_element;
    }
    
    /**
     *
     * @see \X501\ASN1\AttributeValue\AttributeValue::stringValue()
     * @return string
     */
    public function stringValue()
    {
        // return DER encoding as a hexstring
        return "#" . bin2hex($this->_element->toDER());
    }
    
    /**
     *
     * @see \X501\ASN1\AttributeValue\AttributeValue::equalityMatchingRule()
     * @return BinaryMatch
     */
    public function equalityMatchingRule()
    {
        return new BinaryMatch();
    }
    
    /**
     *
     * @see \X501\ASN1\AttributeValue\AttributeValue::rfc2253String()
     * @return string
     */
    public function rfc2253String()
    {
        return $this->stringValue();
    }
    
    /**
     *
     * @see \X501\ASN1\AttributeValue\AttributeValue::_transcodedString()
     * @return string
     */
    protected function _transcodedString()
    {
        return $this->stringValue();
    }
}
