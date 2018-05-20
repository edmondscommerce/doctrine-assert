#!/usr/bin/env bash

binDir='vendor/bin/';

echo "
    Running PHPStan
";

${binDir}phpstan analyse src tests --level max;

echo "
    Running PHPMD
";

${binDir}phpmd src,tests text cleancode,codesize,controversial,design,naming,unusedcode;

echo "
    Running PHPUnit
";

phpunitCommand="${binDir}phpunit tests";

if [[ XDEBUG_PHPUNIT == 'yes' ]]
then
    phpunitCommand="XDEBUG_CONFIG='idekey=PHPSTORM' ${phpunitCommand}";
fi

php ${phpunitCommand};