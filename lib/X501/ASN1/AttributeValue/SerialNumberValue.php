<?php

namespace X501\ASN1\AttributeValue;

use X501\ASN1\AttributeType;
use X501\ASN1\AttributeValue\Feature\PrintableStringValue;


/**
 * 'serialNumber' attribute value
 *
 * @link
 *       https://www.itu.int/ITU-T/formal-language/itu-t/x/x520/2012/SelectedAttributeTypes.html#SelectedAttributeTypes.serialNumber
 */
class SerialNumberValue extends AttributeValue
{
	use PrintableStringValue;
	
	public function __construct($value) {
		$this->_oid = AttributeType::OID_SERIAL_NUMBER;
		$this->_string = $value;
	}
}
