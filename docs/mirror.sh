#!/bin/sh
# @see https://myservers.rapidswitch.com/Api/Help/
source .env
HELP_URL='https://myservers.rapidswitch.com/Api/Help/'
wget -e robots=off -m -np ${HELP_URL} \
  --header='Cookie: .MYSERVERSAUTH='"$MYSERVERSAUTH"';'
