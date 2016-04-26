<?php

namespace X501\ASN1\AttributeValue;

use X501\ASN1\AttributeType;
use X501\ASN1\AttributeValue\Feature\PrintableStringValue;


class CountryNameValue extends AttributeValue
{
	use PrintableStringValue;
	
	public function __construct($value) {
		$this->_oid = AttributeType::OID_COUNTRY_NAME;
		$this->_string = $value;
	}
}
