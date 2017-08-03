<?php

namespace X501\ASN1\AttributeValue;

use X501\ASN1\AttributeType;
use X501\ASN1\AttributeValue\Feature\PrintableStringValue;

/**
 * 'countryName' attribute value
 *
 * @link
 *       https://www.itu.int/ITU-T/formal-language/itu-t/x/x520/2012/SelectedAttributeTypes.html#SelectedAttributeTypes.countryName
 */
class CountryNameValue extends PrintableStringValue
{
    /**
     * Constructor.
     *
     * @param string $value String value
     */
    public function __construct($value)
    {
        $this->_oid = AttributeType::OID_COUNTRY_NAME;
        parent::__construct($value);
    }
}
