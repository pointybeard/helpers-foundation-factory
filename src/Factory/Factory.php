<?php

declare(strict_types=1);

namespace pointybeard\Helpers\Foundation\Factory;

if (!function_exists(__NAMESPACE__.'\create')) {
    function create(string $alias, string $templateNamespace, ?string $expectedClassType = null): string
    {
        if (class_exists($alias)) {
            throw new \Exception(sprintf('Unable to create factory. Returned: Class %s already exists in global scope', $alias));
        }
        $classname = get_class(new class() extends AbstractFactory {
            public function getTemplateNamespace(): string
            {
                [$templateNamespace, ] = FactoryRegistry::lookup(self::class);

                return $templateNamespace;
            }

            public function getExpectedClassType(): ?string
            {
                [, $expectedClassType] = FactoryRegistry::lookup(self::class);

                return $expectedClassType;
            }
        });
        class_alias($classname, $alias);
        FactoryRegistry::register(
            $classname,
            $templateNamespace,
            $expectedClassType
        );

        return $alias;
    }
}
