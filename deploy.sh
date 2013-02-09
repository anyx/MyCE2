#!/bin/sh
${1?"Укажите параметром окружение для которого производится развертывание. Например: ./deploy.sh prod"}
composer install
app/console assets:install --symlink --env=$1
app/console assetic:dump --env=$1
app/console cache:clear --env=$1