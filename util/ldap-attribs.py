#!/usr/bin/python
#
# Parse OpenLDAP ldif schema file and generate "PHP-like code"
# for attribute OID mapping.
#
# Query schema from the server:
# ldapsearch -H ldapi:/// -Y external -b 'cn=schema,cn=config' -LLL \
#   'objectClass=*' > schema.ldif
# 
# Generate PHP code:
# ./ldap-attribs.py schema.ldif > attr.txt
#

import re
import sys
import os
import ldif
import collections
from pprint import pprint


class SchemaParser:
	def __init__(self, path):
		with open(path) as f:
			self.recordlist = ldif.LDIFRecordList(f)
			self.recordlist.parse()
		self.oidmap = self._generate_oid_lookup()
	
	def _generate_oid_lookup(self):
		oidmap = {}
		for dn, record in self.recordlist.all_records:
			if not "olcObjectIdentifier" in record:
				continue
			for entry in record["olcObjectIdentifier"]:
				m = re.match("(?:{\d+})?(\w+) (.*)", entry)
				oid = m.group(2)
				oid = re.sub("(.+):", (lambda m: oidmap[m.group(1)]), oid)
				oidmap[m.group(1)] = oid
		return oidmap
	
	def _substitute_oid(self, oid):
		return re.sub("(.+):", (lambda m: self.oidmap[m.group(1)]), oid)
	
	def _parse_attribute_entry(self, entry):
		ptrn = (
			".*?\(\s*"
				"([^\s]+)"	# oid
			"\s*NAME\s*"
			"(?:"
				"('[\w\-]+')"	# single name
				"|"
				"\(\s*"		# multiple names
					"(.+?)"
				"\s*\)"
			")"
		)
		m = re.match(ptrn, entry)
		if not m:
			raise Exception("Line " + entry + " doesnt match")
		oid = self._substitute_oid(m.group(1))
		if m.group(2):
			names = [m.group(2).strip("'")]
		else:
			names = [n.strip("'") for n in m.group(3).split(" ")]
		return oid, names
	
	def _oid_sorter(self, a, b):
		a_parts = [int(x) for x in a[0].split(".")]
		b_parts = [int(x) for x in b[0].split(".")]
		for x, y in zip(a_parts, b_parts):
			if x == y:
				continue
			return -1 if x < y else 1
		if len(a_parts) == len(b_parts):
			return 0
		return 1 if len(a_parts) < len(b_parts) else -1
	
	def get_attribs(self):
		oids = {}
		for dn, record in self.recordlist.all_records:
			if not "olcAttributeTypes" in record:
				continue
			for entry in record["olcAttributeTypes"]:
				oid, names = self._parse_attribute_entry(entry)
				# skip IANA-registered Private Enterprises arc
				if oid.startswith("1.3.6.1.4.1"):
					continue
				oids[oid] = names
		return collections.OrderedDict(sorted(oids.items(), self._oid_sorter))

parser = SchemaParser(sys.argv[1])
oids = parser.get_attribs()
for oid, names in oids.items():
	print ('"' + oid + '" => ' + 
		"[" + ", ".join(['"' + name + '"' for name in names]) +"],")
