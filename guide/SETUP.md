# 1. [install and run laravel](https://laravel.com/docs/9.x#installation-via-composer) 
```bash 
composer create-project laravel/laravel laravel-project
 
cd example-app
 
php artisan serve
```

# 2. [Install Auth use laravel/ui](https://www.itsolutionstuff.com/post/laravel-9-bootstrap-auth-scaffolding-tutorialexample.html)

```bash 
composer require laravel/ui

php artisan ui bootstrap --auth

npm install && npm run dev

php artisan migrate

```

# 3. [Install authorize with laravel](https://spatie.be/docs/laravel-permission/v5/installation-laravel) 
```bash 
 composer require spatie/laravel-permission
```
- Optional: The service provider will automatically get registered. Or you may manually add the service provider in your config/app.php file:

```php 
'providers' => [
    // ...
    Spatie\Permission\PermissionServiceProvider::class,
];
```

```bash 
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
 php artisan optimize:clear
 php artisan migrate

```

# 4. [install PHPCS and test](https://gitlab.com/erkhuy/tech-laravel-react)
```bash 
composer require --dev squizlabs/php_codesniffer
# syntax: phpcs --standard=PSR2 <folder patch>
# example:

echo "Testing PSR-2"
phpcs --standard=PSR2 --sniffs=Generic.PHP.LowerCaseConstant tests
phpcs --standard=PSR2 app/Traits
phpcs --standard=PSR2 app/Models
phpcs --standard=PSR2 app/Services
phpcs --standard=PSR2 app/Repositories
phpcs --standard=PSR2 app/Http/Controllers
phpcs --standard=PSR2 app/Observers

```


