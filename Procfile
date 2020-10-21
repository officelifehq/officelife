web: vendor/bin/heroku-php-apache2 /public
queue: php artisan queue:work --sleep=3 --tries=3
release: php artisan setup --force -vvv
