# Laravel Previously Deleted

This package stores attributes of deleted models.

## Installation

```
composer require romanzipp/laravel-previously-deleted
```

Or add `romanzipp/laravel-previously-deleted` to your `composer.json`

```
"romanzipp/laravel-previously-deleted": "*"
```

Run composer update to pull the latest version.

**If you use Laravel 5.5+ you are already done, otherwise continue:**

```php
romanzipp\PreviouslyDeleted\Providers\PreviouslyDeletedProvider::class,
```

Add Service Provider to your app.php configuration file:

## Configuration

Copy configuration to config folder:

```
$ php artisan vendor:publish --provider=romanzipp\PreviouslyDeleted\Providers\PreviouslyDeletedProvider
```
