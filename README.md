# Laravel Previously Deleted

[![Latest Stable Version](https://img.shields.io/packagist/v/romanzipp/laravel-previously-deleted.svg?style=flat-square)](https://packagist.org/packages/romanzipp/laravel-previously-deleted)
[![Total Downloads](https://img.shields.io/packagist/dt/romanzipp/laravel-previously-deleted.svg?style=flat-square)](https://packagist.org/packages/romanzipp/laravel-previously-deleted)
[![License](https://img.shields.io/packagist/l/romanzipp/laravel-previously-deleted.svg?style=flat-square)](https://packagist.org/packages/romanzipp/laravel-previously-deleted)

This package stores selected attributes of Models before deletion.

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
$ php artisan vendor:publish --provider="romanzipp\PreviouslyDeleted\Providers\PreviouslyDeletedProvider"
```

Run the migration:

```
$ php artisan migrate
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

The validation rule takes 2 arguments: `not_deleted:{table}[,{attribute}]`

- `table`: The queried table name. In this exmaple: `users`.
- `attribute`: The model attribute. If not given, the input name will be used.

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

## Extended Usage

### Storing hashed values

When storing personal information you should create hashes to respect your users privacy.

**Store plain-text values**

```php
protected $storeDeleted = [
    'username',
    'email',
];
```

With the GDPR (DSGVO) a user has the right to request a full deletion of all personal information, including email address, username etc.
If you're affected by this, you should make use of hashing algorythms to prevent any harm of privacy.

**Store hashed values**

```php
protected $storeDeleted = [
    'username' => 'sha1',
    'email' => 'md5',
];
```

### Storing soft deletes

By default, the package only stores attributes if the model is being force-deleted.

To enable storing attributes even in soft-deletion, set the `ignore_soft_deleted` config value to `false`.

```php
return [

    // ...

    'ignore_soft_deleted' => false,
];

```
