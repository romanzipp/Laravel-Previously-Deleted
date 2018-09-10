# Laravel Previously Deleted

This package stores selected attributes of deleted models.

## Why?

> Todo

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

## Usage

This example shows the usage with a User model and stored "username" and "email" attributes.

### Add Model Trait

```php
use romanzipp\PreviouslyDeleted\Traits\SavePreviouslyDeleted;

class User extends Model
{
    use SavePreviouslyDeleted;

    protected $storeDeleted = [
        'username',
        'email',
    ];
}
```

### Add Validation Rule

```php
public function store(Request $request)
{
    $request->validate([
        'name' => ['required', 'not_deleted:users,username'],
        'email' => ['required', 'not_deleted:users'],
        'password' => ['required', 'min:6']
    ]);

    User::create([
        'username' => $request->input('name'),
        'email' => $request->input('email'),
        'password' => bcrypt($request->input('password')),
    ]);
}
```
