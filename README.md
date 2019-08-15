![Laravel Theme](http://kerembahcivan.com/opensource/laravel-theme.jpg)

# Laravel Template Builder
This package creates multiple managed theme infrastructure for Laravel.


## Getting Started

### 1. Install

Run the install command:

```bash
composer require bkeremm/laravel-theme dev-master
```

### 2. Register (for Laravel < 5.5)

Register the service provider in `config/app.php`

```php
Cankod\Theme\ServiceProvider::class,
```

Add alias if you want to use the facade.

```php
'Theme' => Cankod\Theme\Facade::class,
```

### 3. Publish

Publish config file.

```bash
php artisan vendor:publish --tag=theme
```

### 4. Configure

You can change the options of your app from `config/theme.php` file

## Usage

### Facade
```php
<link rel="stylesheet" href="{{ Theme::asset('app.css') }}">
<script type="javascript" src="{{ Theme::asset('app.js') }}"> 
```
or you can create your css and js files automatically by using assetLink helper.
```php
Theme::assetLink('app.css'); // Output: <link rel="stylesheet" href="/themes/default/css/app.css">
Theme::assetLink('app.js'); 
```

### What's next?
- [ ] Advanced View Files
- [ ] Webpack Build

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Credits

- [Kerem Bahcivan](https://kerembahcivan.com)

## License

The MIT License (MIT). Please see [LICENSE](LICENSE.md) for more information.
