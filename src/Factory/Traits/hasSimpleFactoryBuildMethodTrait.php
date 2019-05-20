<?php

declare(strict_types=1);

namespace pointybeard\Helpers\Foundation\Factory\Traits;

trait hasSimpleFactoryBuildMethodTrait
{
    public static function build(string $name): object
    {
        $concreteClass = self::instanciate(
            self::generateTargetClassName($name)
        );

        return $concreteClass;
    }
}
