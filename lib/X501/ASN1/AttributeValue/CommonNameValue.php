<?php

namespace X501\ASN1\AttributeValue;

use X501\ASN1\AttributeType;
use X501\ASN1\AttributeValue\Syntax\DirectoryString;


class CommonNameValue extends DirectoryString
{
	public function __construct($value, $string_tag = DirectoryString::UTF8) {
		$this->_oid = AttributeType::OID_COMMON_NAME;
		parent::__construct($value, $string_tag);
	}
}
