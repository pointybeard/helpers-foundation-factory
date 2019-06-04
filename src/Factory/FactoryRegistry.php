<?php

declare(strict_types=1);

namespace pointybeard\Helpers\Foundation\Factory;

/**
 * The FactoryRegistry is used by Factory\create() to keep a record of all
 * dynamically created factory classes.
 */
final class FactoryRegistry
{
    private static $register = [];

    private function __construct()
    {
    }

    public static function register(string $name, string $templateNamespace, ?string $expectedClassType = null): void
    {
        if (in_array($name, self::$register)) {
            throw new \Exception(sprintf('Factory %s has already been registered.', $name));
        }
        self::$register[$name] = [$templateNamespace, $expectedClassType];
    }

    public static function lookup(string $name): ?array
    {
        return self::$register[$name] ?? null;
    }
}
