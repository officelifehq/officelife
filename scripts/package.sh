#!/bin/bash

set -xeo pipefail

SELF_PATH=$(cd -P -- "$(dirname -- "$0")" && /bin/pwd -P)
source $SELF_PATH/realpath.sh
ROOT=$(realpath $SELF_PATH/..)

version=$1
if [ "$version" == "" ]; then
  echo "Version parameter is mandatory" >&2
  exit 1
fi

set -v

echo "$version" | tee $ROOT/config/version

# BUILD
composer install --no-progress --no-interaction --no-suggest --prefer-dist --optimize-autoloader
php artisan lang:generate
yarn install
yarn production

# PACKAGE
composer install --no-progress --no-interaction --no-suggest --prefer-dist --optimize-autoloader --no-dev

r=officelife-$version
mkdir -p $r/database
ln -s $ROOT/.env.example $r/
ln -s $ROOT/artisan $r/
ln -s $ROOT/CHANGELOG $r/
ln -s $ROOT/composer.json $r/
ln -s $ROOT/composer.lock $r/
ln -s $ROOT/LICENSE $r/
ln -s $ROOT/package.json $r/
ln -s $ROOT/README.md $r/
ln -s $ROOT/server.php $r/
ln -s $ROOT/webpack.mix.js $r/
ln -s $ROOT/yarn.lock $r/
ln -s $ROOT/app $r/
ln -s $ROOT/bootstrap $r/
ln -s $ROOT/config $r/
ln -s $ROOT/docs $r/
ln -s $ROOT/public $r/
ln -s $ROOT/resources $r/
ln -s $ROOT/routes $r/
ln -s $ROOT/vendor $r/

ln -s $ROOT/database/factories $r/database/
ln -s $ROOT/database/migrations $r/database/
ln -s $ROOT/database/seeds $r/database/

mkdir -p $r/storage/app/public
mkdir -p $r/storage/logs
mkdir -p $r/storage/framework/cache
mkdir -p $r/storage/framework/views
mkdir -p $r/storage/framework/sessions

tar chfj $ROOT/$r.tar.bz2 --exclude .gitignore --exclude .gitkeep $r

echo "::set-output name=package::$ROOT/$r.tar.bz2"
