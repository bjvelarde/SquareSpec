#!/bin/bash
SCRIPT=$(readlink -f $0)
SCRIPTPATH=`dirname $SCRIPT`
CMD="php $SCRIPTPATH/square.php"
ERRMSG="Usage: square (<spec-file>)"
if [[ $1 ]]
then
  $CMD $1
else
  $CMD
fi