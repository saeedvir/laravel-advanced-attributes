# Laravel Advanced Attributes


***The `laravel-advanced-attributes` package is a tool designed to help Laravel developers ....

# Requirements
***

- `PHP: ^8.0`
- `Laravel Framework: ^9.0`

| Attributes | L9                 | L10                | L11                | L12                |
|------------|--------------------|--------------------|
| 1.0        | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: |

# Installation
***
```
composer require saeedvir/laravel-advanced-attributes
```
After publish config files.<br>
```bash
php artisan vendor:publish --provider="Saeedvir\LaravelAdvancedAttributes\LaravelAdvancedAttributesServiceProvider"
```
After publish, you migrate the migration file.
```
php artisan migrate
```

# Usage
First, you use trait in model.
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Milwad\LaravelAttributes\Traits\Attributable;

class Product extends Model
{
    use HasFactory, HasAttributes;
}
```

After, you have access to `attributes` relation and etc... .

## Save attribute


```php

```

## Save attribute multiple



```php

```

## Get attributes with query


```php

```

## Check attribute value is exists


```php

```

## Check attribute value is exists



```php

```

## Delete all attributes



```php

```

## Delete special attributes



```php

```

## Delete special attributes by title



```php

```

## Delete special attributes by value



```php
```

## Testing

Run the tests with:

``` bash
vendor/bin/pest
composer test
composer test-coverage
```

## Customize

If you want change migration table name or change default model you can use `laravel-advanced-attributes` config that exists in `config` folder.

```php
<?php

return [
    /*
     * Table config
     *
     * Here it's a config of migrations.
     */
    'tables' => [
        /*
         * Get table name of migration.
         */
        'attributes' => 'attributes',
        'attributables' => 'attributables',
    ],

    /*
     * Model class name for attributes and attributables table.
     */
    'attributes_model' => \Saeedvir\LaravelAdvancedAttributes\Attribute::class,
    'attributables_model' =>\Saeedvir\LaravelAdvancedAttributes\Attributable::class,
];
```

# License
* This package is created and modified by <a href="https://github.com/saeedvir" target="_blank">Milwad Khosravi</a> for Laravel >= 9 and is released under the MIT License.

## Contributing

This project exists thanks to all the people who
contribute. [CONTRIBUTING](https://github.com/milwad-dev/laravel-attributes/graphs/contributors)

<a href="https://github.com/saeedvir/laravel-advanced-attributes/graphs/contributors"><img src="https://opencollective.com/laravel-advanced-attributes/contributors.svg?width=890&button=false" /></a>

## Security

If you've found a bug regarding security please mail [milwad.dev@gmail.com](mailto:saeed.es91@gmail.com) instead of
using the issue tracker.

## Donate

If this package is helpful for you, you can buy a coffee for me :) ❤️

- reymit: https://reymit.ir/saeedvir
