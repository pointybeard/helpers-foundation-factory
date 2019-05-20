# PHP Helpers: Factory Foundation Classes

-   Version: v1.0.0
-   Date: May 20 2019
-   [Release notes](https://github.com/pointybeard/helpers-foundation-factory/blob/master/CHANGELOG.md)
-   [GitHub repository](https://github.com/pointybeard/helpers-foundation-factory)

Provides foundation factory classes and factory design pattern functionality

## Installation

This library is installed via [Composer](http://getcomposer.org/). To install, use `composer require pointybeard/helpers-foundation-factory` or add `"pointybeard/helpers-foundation-factory": "~1.0"` to your `composer.json` file.

And run composer to update your dependencies:

    $ curl -s http://getcomposer.org/installer | php
    $ php composer.phar update

### Requirements

There are no particuar requirements for this library other than PHP 7.2 or greater.

To include all the [PHP Helpers](https://github.com/pointybeard/helpers) packages on your project, use `composer require pointybeard/helpers` or add `"pointybeard/helpers": "~1.0"` to your composer file.

## Usage

Include this library in your PHP files with `use pointybeard\Helpers\Foundation\Factory` and extend the `Factory\AbstractFactory` class like so:

```php
<?php

declare(strict_types=1);

namespace MyApp;

include __DIR__.'/vendor/autoload.php';

use pointybeard\Helpers\Foundation\Factory;

interface CarInterface
{
    public function name(): string;
}

abstract class AbstractCar implements CarInterface
{
}

class Volvo extends AbstractCar
{
    public function name(): string
    {
        return 'Volvo';
    }
}

final class CarFactory extends Factory\AbstractFactory
{
    public static function getTemplateNamespace(): string
    {
        return '\\MyApp\\%s';
    }

    public static function getExpectedClassType(): ?string
    {
        return '\\MyApp\\AbstractCar';
    }

    use Factory\Traits\hasSimpleFactoryBuildMethodTrait;
}

$car = CarFactory::build('Volvo');
var_dump($car->name());
// string(5) "Volvo"

try {
    CarFactory::build('Peugeot');
} catch (Factory\Exceptions\UnableToInstanciateConcreteClassException $ex) {
    echo 'Error! Unable to build a Peugeot. Returned: '.$ex->getMessage().PHP_EOL;
}
// Error! Unable to build a Peugeot. Returned: Class \MyApp\Peugeot does not exist

class Cabbage
{
    public function name(): string
    {
        return 'Not a car!';
    }
}

try {
    CarFactory::build('Cabbage');
} catch (Factory\Exceptions\UnableToInstanciateConcreteClassException $ex) {
    echo 'Error! Unable to build a Cabbage. Returned: '.$ex->getMessage().PHP_EOL;
}
// Error! Unable to build a Cabbage. Returned: Class \MyApp\Cabbage is not of expected type \MyApp\AbstractCar

```

## Support

If you believe you have found a bug, please report it using the [GitHub issue tracker](https://github.com/pointybeard/helpers-foundation-factory/issues),
or better yet, fork the library and submit a pull request.

## Contributing

We encourage you to contribute to this project. Please check out the [Contributing documentation](https://github.com/pointybeard/helpers-foundation-factory/blob/master/CONTRIBUTING.md) for guidelines about how to get involved.

## License

"PHP Helpers: Factory Foundation Classes" is released under the [MIT License](http://www.opensource.org/licenses/MIT).
