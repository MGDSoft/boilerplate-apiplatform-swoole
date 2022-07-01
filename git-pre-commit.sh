#!/usr/bin/env bash

set -e

echo "[GIT-PRE-COMMIT] Loading...\n\n"

DOCKER_EXECUTION="docker-compose exec -T backend bash -c"
PHP_CS_FIXER="./vendor/bin/php-cs-fixer"
PHP_STAN="./vendor/bin/phpstan"
EXECUTE_TEST="./vendor/bin/phpunit"

if [ $(git diff --cached --name-only --diff-filter=ACMRTUXB | grep -q '\.php$') ] || [ "$1" = "check" ]; then

    PHP_CS_FIXER_CMD="$PHP_CS_FIXER fix --verbose --config=.php-cs-fixer.dist.php $PHP_CS_FIXER_EXTRA_PARAMS ${STAGED_FILES[@]} "

    if [ "$1" = "check" ]; then
      STAGED_FILES=''
      PHP_CS_FIXER_CMD="$PHP_CS_FIXER_CMD fix --dry-run --diff "
    else
      STAGED_FILES=" -- $(git diff --cached --name-only --diff-filter=ACMRTUXB | grep '\.php$' | sed 's/app\///' | sed ':a;N;$!ba;s/\n/ /g' )";
    fi

    set -x #echo on
    $DOCKER_EXECUTION "
        $PHP_STAN analyse ${STAGED_FILES[@]} \
        && $PHP_CS_FIXER_CMD \
        && $EXECUTE_TEST
    ";

fi

echo "[GIT-PRE-COMMIT] End OK"