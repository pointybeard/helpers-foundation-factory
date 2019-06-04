# PHP Helpers: Factory Foundation Classes

-   Version: v1.0.2
-   Date: June 04 2019
-   [Release notes](https://github.com/pointybeard/helpers-foundation-factory/blob/master/CHANGELOG.md)
-   [GitHub repository](https://github.com/pointybeard/helpers-foundation-factory)

Provides foundation factory classes and factory design pattern functionality

## Installation

This library is installed via [Composer](http://getcomposer.org/). To install, use `composer require pointybeard/helpers-foundation-factory` or add `"pointybeard/helpers-foundation-factory": "~1.0.0"` to your `composer.json` file.

And run composer to update your dependencies:

    $ curl -s http://getcomposer.org/installer | php
    $ php composer.phar update

### Requirements

There are no particuar requirements for this library other than PHP 7.2 or greater.

To include all the [PHP Helpers](https://github.com/pointybeard/helpers) packages on your project, use `composer require pointybeard/helpers`

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
    public function name(): string
    {
        return (new \ReflectionClass(static::class))->getShortName();
    }

    public function __toString(): string
    {
        return "Hi! I'm a {$this->name()}.";
    }
}

// Create a factory called MyApp\CarFactory
Factory\create(
    __NAMESPACE__.'\\CarFactory',
    __NAMESPACE__.'\\%s',
    __NAMESPACE__.'\\AbstractCar'
);

// Create custom factory class
class PeugeotFactory extends Factory\AbstractFactory
{
    public function getTemplateNamespace(): string
    {
        return __NAMESPACE__.'\\%s';
    }

    public function getExpectedClassType(): ?string
    {
        return __NAMESPACE__.'\\Peugeot';
    }
}

// Basic car
class Volvo extends AbstractCar
{
}

// This adds a constructor which expects a model and vin number. They need
// to be passed in when CarFactory::build() is called
class Peugeot extends AbstractCar
{
    private $model;
    private $vin;

    public function __construct(string $model, string $vin)
    {
        $this->model = $model;
        $this->vin = $vin;
    }

    public function __get(string $name)
    {
        return $this->$name;
    }

    public function __toString(): string
    {
        return parent::__toString()." I am a model {$this->model} with vehicle identification number {$this->vin}";
    }
}

// Does not extend AbstractCar.. i.e. a Cabbage is not a type of car
class Cabbage
{
}

$car = CarFactory::build('Volvo');
var_dump((string) $car);
// string(16) "Hi! I'm a Volvo."

$car = PeugeotFactory::build('Peugeot', '206', 'VF32HRHYF43242177');
var_dump((string) $car);
// string(88) "Hi! I'm a Peugeot. I am a model 206 with vehicle identification number VF32HRHYF43242177"

try {
    $car = PeugeotFactory::build('Volvo', 'XC60', 'YV1DZ8256C227123');
} catch (Factory\Exceptions\UnableToInstanciateConcreteClassException $ex) {
    echo 'Error: Cannot build a Volvo in the Peugeot factory!'.PHP_EOL;
}
// Error: Cannot build a Volvo in the Peugeot factory!

try {
    CarFactory::build('Suzuki');
} catch (Factory\Exceptions\UnableToInstanciateConcreteClassException $ex) {
    echo 'Error: Unable to build a Suzuki. Returned: '.$ex->getMessage().PHP_EOL;
}
// Error: Unable to build a Suzuki. Returned: Class \MyApp\Suzuki does not exist

try {
    CarFactory::build('Cabbage');
} catch (Factory\Exceptions\UnableToInstanciateConcreteClassException $ex) {
    echo 'Error: Unable to build a Cabbage. Returned: '.$ex->getMessage().PHP_EOL;
}
// Error: Unable to build a Cabbage. Returned: Class \MyApp\Cabbage is not
// of expected type \MyApp\AbstractCar


```

## Support

If you believe you have found a bug, please report it using the [GitHub issue tracker](https://github.com/pointybeard/helpers-foundation-factory/issues),
or better yet, fork the library and submit a pull request.

## Contributing

We encourage you to contribute to this project. Please check out the [Contributing documentation](https://github.com/pointybeard/helpers-foundation-factory/blob/master/CONTRIBUTING.md) for guidelines about how to get involved.

## License

"PHP Helpers: Factory Foundation Classes" is released under the [MIT License](http://www.opensource.org/licenses/MIT).
