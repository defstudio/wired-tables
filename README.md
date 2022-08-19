
[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/support-ukraine.svg?t=1" />](https://supportukrainenow.org)

# Laravel Livewire Datatables

[![Latest Version on Packagist](https://img.shields.io/packagist/v/defstudio/wired-tables.svg?style=flat-square)](https://packagist.org/packages/defstudio/wired-tables)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/defstudio/wired-tables/run-tests?label=tests)](https://github.com/defstudio/wired-tables/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/defstudio/wired-tables/Check%20&%20fix%20styling?label=code%20style)](https://github.com/defstudio/wired-tables/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/defstudio/wired-tables.svg?style=flat-square)](https://packagist.org/packages/defstudio/wired-tables)

This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Support us

[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/wired-tables.jpg?t=1" width="419px" />](https://spatie.be/github-ad-click/wired-tables)

We invest a lot of resources into creating [best in class open source packages](https://spatie.be/open-source). You can support us by [buying one of our paid products](https://spatie.be/open-source/support-us).

We highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using. You'll find our address on [our contact page](https://spatie.be/about-us). We publish all received postcards on [our virtual postcard wall](https://spatie.be/open-source/postcards).

## Installation

You can install the package via composer:

```bash
composer require defstudio/wired-tables
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="wired-tables-config"
```

This is the contents of the published config file:

```php
return [
];
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="wired-tables-views"
```


## Tailwind configuration

In order to keep wired tables tailwind classes, add this to your `tailwind.config.js`:

```js
module.exports = {
    content: [
        //...
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/wire-elements/modal/resources/views/*.blade.php',
        //...
    ],
    theme: {
        extend: {},
    },
    plugins: [],
    prefix: 'tw-',
    corePlugins: {
        preflight: false,
    }
}
```

### Using tailwind along with other frameworks (i.e. bootstrap)

set `style = tailwind_3_prefixed` config in `configs/wired-tables.php` (see above for info on how to publish config file)

and add these to your `tailwind.config.js`:

```js
module.exports = {
    content: [
        //...
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/wire-elements/modal/resources/views/*.blade.php',
    ],
    
    //...
    
    prefix: 'tw-',
    corePlugins: {
        //...
        preflight: false,
    }
}
```

## Usage

```php
$wiredTables = new DefStudio\WiredTables();
echo $wiredTables->echoPhrase('Hello, DefStudio!');
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](https://github.com/spatie/.github/blob/main/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Fabio Ivona](https://github.com/def-studio)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.



## TODO DOCS

### Views can be overridden by publishing them
```bash
php artisan vendor:publish --tag="wired-tables-views"
```

### Views can be overridden on a single table also:
```php
MyTable extends WiredTable{
    public function mainView(): string
    {
        return 'custom-main-view';
    }
```

the following view methods can be overridden:
- `mainView()`: the main container view for the table
- `tableView()`: the main table view
