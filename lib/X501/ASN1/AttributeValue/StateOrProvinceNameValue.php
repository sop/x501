<?php

namespace X501\ASN1\AttributeValue;

use X501\ASN1\AttributeType;
use X501\ASN1\AttributeValue\Feature\DirectoryString;


/**
 * 'stateOrProvinceName' attribute value.
 *
 * @link
 *       https://www.itu.int/ITU-T/formal-language/itu-t/x/x520/2012/SelectedAttributeTypes.html#SelectedAttributeTypes.stateOrProvinceName
 */
class StateOrProvinceNameValue extends DirectoryString
{
	public function __construct($value, $string_tag = DirectoryString::UTF8) {
		$this->_oid = AttributeType::OID_STATE_OR_PROVINCE_NAME;
		parent::__construct($value, $string_tag);
	}
}
