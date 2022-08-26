cp .env.example .env
composer dump-autoload
composer install --prefer-dist --no-ansi --no-interaction --no-progress --no-scripts
php artisan key:generate
php artisan cache:clear
php artisan config:clear
php artisan migrate
php artisan db:seed
php artisan storage:link
