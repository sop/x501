#!/usr/bin/python3
#
# Parse OpenLDAP ldif schema file and generate "PHP-like code"
# for attribute OID mapping.
#
# Install python-ldap.
# eg. via pip:
#   pip3 install python-ldap
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
import collections
import functools
import ldif


class SchemaParser:
    def __init__(self, path):
        with open(path) as file:
            self.recordlist = ldif.LDIFRecordList(file)
            self.recordlist.parse()
        self.oidmap = self._generate_oid_lookup()

    def _generate_oid_lookup(self):
        oidmap = {}
        for _dn, record in self.recordlist.all_records:
            if not "olcObjectIdentifier" in record:
                continue
            for entry in record["olcObjectIdentifier"]:
                match = re.match(r"(?:{\d+})?(\w+) (.*)",
                                 entry.decode('utf-8'))
                oid = match.group(2)
                oid = re.sub(r"(.+):", (lambda m: oidmap[m.group(1)]), oid)
                oidmap[match.group(1)] = oid
        return oidmap

    def _substitute_oid(self, oid):
        return re.sub(r"(.+):", (lambda m: self.oidmap[m.group(1)]), oid)

    def _parse_attribute_entry(self, entry):
        ptrn = (
            r".*?\(\s*"
            r"([^\s]+)"     # oid
            r"\s*NAME\s*"
            r"(?:"
            r"('[\w\-]+')"  # single name
            r"|"
            r"\(\s*"        # multiple names
            r"(.+?)"
            r"\s*\)"
            r")"
        )
        match = re.match(ptrn, entry)
        if not match:
            raise Exception("Line " + entry + " doesnt match")
        oid = self._substitute_oid(match.group(1))
        if match.group(2):
            names = [match.group(2).strip("'")]
        else:
            names = [n.strip("'") for n in match.group(3).split(" ")]
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
        for _dn, record in self.recordlist.all_records:
            if not "olcAttributeTypes" in record:
                continue
            for entry in record["olcAttributeTypes"]:
                oid, names = self._parse_attribute_entry(entry.decode('utf-8'))
                # skip IANA-registered Private Enterprises arc
                if oid.startswith("1.3.6.1.4.1"):
                    continue
                oids[oid] = names
        return collections.OrderedDict(
            sorted(oids.items(), key=functools.cmp_to_key(self._oid_sorter)))


def generate():
    for oid, names in SchemaParser(sys.argv[1]).get_attribs().items():
        print("'" + oid + "' => " +
              "[" + ", ".join(["'" + name + "'" for name in names]) + "],")


if __name__ == "__main__":
    generate()
