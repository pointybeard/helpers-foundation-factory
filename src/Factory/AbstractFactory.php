<?php

declare(strict_types=1);

namespace pointybeard\Helpers\Foundation\Factory;

abstract class AbstractFactory implements Interfaces\FactoryInterface
{
    // Prevents the class from being instanciated
    private function __construct()
    {
    }

    public static function getExpectedClassType(): ?string
    {
        // side effect: returning null will cause self::isExpectedClassType()
        // to always return true.
        return null;
    }

    protected static function generateTargetClassName(string ...$args): string
    {
        // If the number of items in $args is not equal to the
        // number of directives in static::getTemplateNamespace(), an E_WARNING
        // will be thrown when vsprintf() is called. We need to trap this
        // warning and throw it as an exception instead, hence the use of
        // set_error_handler() here.
        set_error_handler(function ($errno, $errstr, $errfile, $errline) {
            throw new \ErrorException($errstr, 0, $errno, $errfile, $errline);
        }, E_WARNING);

        try {
            $result = vsprintf(static::getTemplateNamespace(), $args);
        } catch (\ErrorException $ex) {
            restore_error_handler();
            throw new Exceptions\UnableToGenerateTargetClassNameException(static::class, $ex->getMessage());
        }
        restore_error_handler();

        return $result;
    }

    protected static function isExpectedClassType(object $class): bool
    {
        return null === static::getExpectedClassType() || is_a($class, static::getExpectedClassType(), false);
    }

    protected static function instanciate(string $class): object
    {
        if (!class_exists($class)) {
            throw new Exceptions\UnableToInstanciateConcreteClassException(sprintf(
                'Class %s does not exist',
                $class
            ));
        }

        $object = new $class();

        if (!static::isExpectedClassType($object)) {
            throw new Exceptions\UnableToInstanciateConcreteClassException(sprintf(
                'Class %s is not of expected type %s',
                $class,
                static::getExpectedClassType()
            ));
        }

        return $object;
    }
}
