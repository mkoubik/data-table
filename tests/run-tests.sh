#!/bin/sh
#
# From Nette Framework
# http://gihub.com/nette/nette

# Path to this script's directory
dir=$(cd `dirname $0` && pwd)

# Path to test runner script
runnerScript="$dir/../vendor/nette/tester/Tester/tester.php"
if [ ! -f "$runnerScript" ]; then
	echo "Nette Tester is missing. You can install it using Composer:" >&2
	echo "php composer.phar update --dev." >&2
	exit 2
fi

php "$runnerScript" -j 20 "$@"
error=$?

# Print *.actual content if tests failed
if [ "${VERBOSE-false}" != "false" -a $error -ne 0 ]; then
	for i in $(find . -name \*.actual); do echo "--- $i"; cat $i; echo; echo; done
	exit $error
fi
