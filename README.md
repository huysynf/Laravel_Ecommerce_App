# Setup 
```bash

cp .env.example .env

composer install
php artisan key:generate
php artisa migrate --seed
php artisan cache:clear
php artisan storage:link



```

# file 

- [File design Table](https://drive.google.com/file/d/1f7VBNM6SbSI7PnnvsjTcGCAmN41N_us5/view?usp=sharing)
- [Template](https://drive.google.com/drive/folders/10yQqtDZ0WGzTkzNuqdvjZCW01A_GVbT4?usp=sharing)


## PSR2 check 

```bash 

composer require --dev squizlabs/php_codesniffer


# syntax: phpcs --standard=PSR2 <folder patch>
# example:

echo "Testing PSR-2"
./vendor/bin/phpcs --standard=PSR2 app/Traits
./vendor/bin/phpcs --standard=PSR2 app/Models
./vendor/bin/phpcs --standard=PSR2 app/Http/Controllers
```
## psr2 fix bacsic

```bash 
 composer require  --dev  "squizlabs/php_codesniffer=*"  

./vendor/bin/phpcbf --standard=PSR2 app/Traits
./vendor/bin/phpcbf --standard=PSR2 app/Models
./vendor/bin/phpcbf --standard=PSR2 app/Http/Controllers

```
