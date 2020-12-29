#!/bin/sh
# @see https://github.com/dottedmag/archmage
DOC=https://myservers.rapidswitch.com/Api/Help/Documentation.chm
[[ -f Documentation.chm ]] || wget $DOC
# pip install archmage
archmage -d Documentation.chm
