![Laravel Theme](http://kerembahcivan.com/opensource/laravel-theme.jpg)


# Laravel Template Builder
[![Latest Stable Version](https://poser.pugx.org/bkeremm/laravel-theme/v/stable)](https://packagist.org/packages/bkeremm/laravel-theme)
[![Total Downloads](https://poser.pugx.org/bkeremm/laravel-theme/downloads)](https://packagist.org/packages/bkeremm/laravel-theme)
[![License](https://poser.pugx.org/bkeremm/laravel-theme/license)](https://packagist.org/packages/bkeremm/laravel-theme)

This package creates multiple managed theme infrastructure for Laravel.


## Getting Started

### 1. Install

Run the install command:

```bash
composer require bkeremm/laravel-theme
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
Just run the artisan command to create a new theme.
```bash
php artisan theme:generate
```
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
### Configure
`config/theme.php` contains the following settings.

If you want to change the default created file and folder names with Artisan command, you can do this easily in `config/theme.php`.
```php
    'current_theme' => 'default',

    'views_folder' => [
        'layout'    => 'layouts',
        'component' => 'components',
    ],

    'views_blade' => [
        'index'     => 'index',
        'header'    => 'header',
        'footer'    => 'footer',
        'layout'    => 'main',
    ],
 
    'webpack' => [
        'folder' => [
            'js'    => 'js',
            'css'   => 'sass',
        ],
        'file' => [
            'css'           => 'app.scss',
            'variable'      => '_variables.scss',
            'js'            => 'app.js',
            'bootstrap'     => 'bootstrap.js',
        ]
    ],

    'resource_path'     => 'themes',
    'public_path'       => 'themes',
```

If you want to activate a new theme on the front side, just type the theme name in the current_theme field.

```php
'current_theme' => 'theme_name', 
```

### What's next?
- [X] Webpack Build
- [ ] Advanced View Files

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Credits

- [Kerem Bahcivan](https://kerembahcivan.com)

## License

The MIT License (MIT). Please see [LICENSE](LICENSE.md) for more information.
